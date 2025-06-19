import os
import json
import re
import requests
from bs4 import BeautifulSoup
from playwright.sync_api import sync_playwright

# Fichier final qui contiendra toutes les stats par Pokémon
OUTPUT_STATS_JSON = "pokemon_stats.json"

# Liste des IDs (slugs) des Pokémon disponibles sur Unite-DB
POKEMON_IDS = [
    "absol", "aegislash", "armarouge", "azumarill", "blastoise", "blaziken", "blissey", "buzzwole", "ceruledge",
    "chandelure", "charizard", "cinderace", "clefable", "comfey", "cramorant", "crustle", "darkrai", "decidueye",
    "delphox", "dodrio", "dragapult", "dragonite", "duraludon", "eldegoss", "espeon", "falinks", "garchomp",
    "gardevoir", "gengar", "glaceon", "goodra", "greedent", "greninja", "gyarados", "ho-oh", "hoopa", "inteleon",
    "lapras", "leafeon", "lucario", "machamp", "mamoswine", "meowscarada", "metagross", "mew", "mewtwo-x",
    "mewtwo-y", "mimikyu", "miraidon", "mr-mime", "ninetales", "pikachu", "psyduck", "raichu", "rapidash",
    "sableye", "scizor", "scyther", "slowbro", "snorlax", "suicune", "sylveon", "talonflame", "tinkaton",
    "trevenant", "tsareena", "tyranitar", "umbreon", "urshifu", "venusaur", "wigglytuff", "zacian", "zeraora",
    "zoroark"
]

# Fonction principale Playwright

def extract_stats_with_playwright():
    all_stats = []

    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page()

        for poke_id in POKEMON_IDS:
            url = f"https://unite-db.com/pokemon/{poke_id}"
            print(f"🔍 Accès à {url}")
            try:
                page.goto(url, timeout=20000)
                page.wait_for_load_state("networkidle")

                # Récupération des données depuis les balises <script>
                scripts = page.query_selector_all("script")
                stat_data = None
                for script in scripts:
                    content = script.inner_text()
                    if "statGraphData" in content:
                        match = re.search(r"statGraphData\s*=\s*(\[.*?\])", content, re.DOTALL)
                        if match:
                            stat_data = json.loads(match.group(1))
                            break

                if stat_data and len(stat_data) == 8 and all(len(s) == 15 for s in stat_data):
                    entry = {
                        "name": poke_id.capitalize(),
                        "id": poke_id,
                        "stats": [
                            {
                                "hp": stat_data[0][i],
                                "atk": stat_data[1][i],
                                "def": stat_data[2][i],
                                "atk spé": stat_data[3][i],
                                "def spé": stat_data[4][i],
                                "crit rate": stat_data[5][i],
                                "cooldown reduction": stat_data[6][i],
                                "life steal": stat_data[7][i]
                            }
                            for i in range(15)
                        ]
                    }
                    print(f"✅ Stats récupérées pour {poke_id}")
                    all_stats.append(entry)
                else:
                    print(f"⚠️ Données incomplètes ou absentes pour {poke_id}")

            except Exception as e:
                print(f"❌ Erreur pour {poke_id} : {e}")

        browser.close()

    with open(OUTPUT_STATS_JSON, "w", encoding="utf-8") as f:
        json.dump(all_stats, f, indent=2, ensure_ascii=False)
    print(f"\n✅ Fichier {OUTPUT_STATS_JSON} généré avec succès.")

# Lancer si fichier manquant
if __name__ == "__main__":
    extract_stats_with_playwright()
