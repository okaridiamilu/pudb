#La version final de mon outil de scraping. avec des ajouts de data suplémentaire deja pensé pour des améliorations futurs

import os
import json
import re
from bs4 import BeautifulSoup

def normalize_filenames(folder):
    for filename in os.listdir(folder):
        lowercase_name = filename.lower()
        src = os.path.join(folder, filename)
        dst = os.path.join(folder, lowercase_name)
        if filename != lowercase_name:
            os.rename(src, dst)
            print(f"🔁 Renommé : {filename} → {lowercase_name}")

def extract_pokemon_links(main_html_path):
    with open(main_html_path, "r", encoding="utf-8") as f:
        soup = BeautifulSoup(f, "html.parser")

    links = []
    for a in soup.find_all("a", href=True):
        href = a["href"]
        if "unite-db.com/pokemon/" in href:
            name = href.split("/")[-1].lower() + ".html"
            links.append(name)

    print(f"✅ Trouvé {len(links)} fichiers Pokémon à traiter.")
    return links

def match_context(line, keywords, context_terms, exclusions=None):
    for kw in keywords:
        if kw in line:
            for ctx in context_terms:
                if ctx in line:
                    if exclusions and any(ex in line for ex in exclusions):
                        continue
                    return (True, kw)
    return (False, None)

def extract_data_from_pokemon_file(filepath):
    with open(filepath, "r", encoding="utf-8") as f:
        soup = BeautifulSoup(f, "html.parser")

    character_info = soup.find("div", class_="character-info")
    if not character_info:
        print(f"❌ Aucun bloc 'character-info' trouvé dans {filepath}")
        return {}

    data = {}
    debug_flags = {}

    h1 = character_info.find("h1")
    name_clean = h1.text.strip().split()[0] if h1 else "Unknown"
    poke_id = os.path.splitext(os.path.basename(filepath))[0].lower()

    data["nom"] = name_clean
    data["ID"] = poke_id

    data["style"] = "Unknown"
    data["type-de-dmg"] = "Unknown"
    data["role"] = "Unknown"
    tags = character_info.find("div", class_="tags")
    if tags:
        for pill in tags.find_all("p", class_="pill"):
            cls = pill.get("class", [])
            val = pill.get_text(strip=True)
            if "difficulty" in cls:
                data["style"] = val
            elif "range" in cls:
                data["type-de-dmg"] = val
            elif "role" in cls:
                data["role"] = val

    full_text = soup.get_text(separator="\n").lower()
    lines = full_text.split("\n")

    def has_combined_keywords(main_keys, context_keys, label, exclusions=None):
        for line in lines:
            match, keyword = match_context(line, main_keys, context_keys, exclusions)
            if match:
                debug_flags[label] = keyword
                return True
        return False

    def has_any_keyword(keywords, label):
        for line in lines:
            for kw in keywords:
                if kw in line:
                    debug_flags[label] = kw
                    return True
        return False

    data["sa-capacité-a-pouvoir-heal-les-alliers"] = has_combined_keywords(
        ["heal", "restores hp", "recovers hp", "restoring hp"],
        ["ally", "allies", "teammates", "nearby allies", "friendly"],
        "heal allies"
    )

    data["sa-capacité-a-croud-controle"] = has_any_keyword([
        "immobilize", "stun", "slow", "reduce movement speed", "knock up", "knock back",
        "shove", "push", "repel", "displace", "interrupt", "disrupt"
    ], "crowd control")

    data["sa-capacité-a-SE-shield"] = has_combined_keywords(
        ["shield", "grants a shield", "gains a shield", "becomes shielded", "applies a shield"],
        ["user", "itself", "self", "to itself"],
        "self shield"
    )

    data["sa-capacité-a-shield-les-alliers"] = has_combined_keywords(
        ["grants a shield", "provides a shield", "gives a shield", "applies a shield"],
        ["allies", "nearby allies", "teammates", "friendly"],
        "shield allies"
    )

    data["sa-capacité-a-buff-les-alliers"] = has_combined_keywords(
        ["increase", "boost", "enhance", "raise"],
        ["allies", "teammates", "nearby allies", "friendly"],
        "buff allies"
    )

    data["sa-capacité-a-debuff"] = has_combined_keywords(
        ["reduce", "lower", "decrease"],
        ["attack", "speed", "defense", "special attack", "movement speed", "sp. atk", "sp. def", "enemy", "opponent", "target", "opposing", "foe"],
        "debuff",
        exclusions=["cooldown", "reduce cooldown", "cooldown reduction", "cooldown decreased"]
    )

    data["Scale-HP"] = has_combined_keywords(
        ["damage", "deals damage", "inflicts damage"],
        ["max hp", "remaining hp", "based on hp", "percent of hp", "percentage of hp"],
        "hp based damage"
    )

    data["Execute"] = has_combined_keywords(
        [
            "damage - execute",
            "execute damage",
            "true damage",
            "deals damage equal to",
            "deals true damage"
        ],
        [
            "missing hp",
            "max hp",
            "target's missing hp",
            "target's max hp",
            "below 20% hp",
            "under 20%",
            "percentage of hp",
            "hp threshold"
        ],
        "execute",
        exclusions=[
            "damage increases as hp decreases",
            "bonus damage against low hp",
            "increased damage to low hp targets",
            "damage scales with remaining hp"
        ]
    )



    # Image dynamique depuis pokemondb.net
    manual_overrides = {
        "mr.mime": "mr-mime",
        "mewtwo x": "mewtwo-x",
        "mewtwo y": "mewtwo-y",
        "urshifu rs": "urshifu-rapid-strike"
    }
    name_for_url = manual_overrides.get(poke_id, poke_id.replace('.', '').replace(' ', '-').lower())
    data["image"] = f"https://img.pokemondb.net/sprites/home/normal/{name_for_url}.png"

    if debug_flags:
        print("🔍 Mots-clés déclencheurs:")
        for key, trigger in debug_flags.items():
            print(f"  ✔️ {key} → '{trigger}'")

    return data

def run_scraper_local(main_html, detail_folder, output_json):
    normalize_filenames(detail_folder)
    links = extract_pokemon_links(main_html)
    all_data = []

    for filename in links:
        filepath = os.path.join(detail_folder, filename)
        if os.path.exists(filepath):
            print(f"📄 Lecture de {filename}")
            data = extract_data_from_pokemon_file(filepath)
            all_data.append(data)
        else:
            print(f"❌ Fichier manquant : {filename}")

    with open(output_json, "w", encoding="utf-8") as f:
        json.dump(all_data, f, indent=2, ensure_ascii=False)

    print(f"\n✅ Sauvegardé {len(all_data)} Pokémon dans {output_json}")

if __name__ == "__main__":
    run_scraper_local(
        main_html="main_page.html",
        detail_folder="pokemon_pages",
        output_json="pokemon_data.json"
    )
