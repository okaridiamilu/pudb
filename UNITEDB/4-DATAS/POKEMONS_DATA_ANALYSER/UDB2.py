import os
import json
import re
import requests
import time
from bs4 import BeautifulSoup

def extract_pokemon_links(main_html_path):
    """Extraire les liens des Pokémon à partir du fichier HTML principal."""
    with open(main_html_path, "r", encoding="utf-8") as f:
        soup = BeautifulSoup(f, "html.parser")

    links = []
    for a in soup.find_all("a", href=True):
        href = a["href"]
        if "/pokemon/" in href:  # Modifier cette condition pour inclure tous les Pokémon
            links.append(href)

    print(f"✅ Trouvé {len(links)} liens Pokémon.")
    return links


def sanitize_filename(name):
    """Sanitiser le nom pour qu'il soit compatible avec les noms de fichiers."""
    return re.sub(r'[\\/*?:"<>|]', "", name).strip()


def extract_stats_from_html(html_text, url=None):
    """Extraire les informations détaillées d'un Pokémon à partir du HTML."""
    soup = BeautifulSoup(html_text, "html.parser")
    content = soup.get_text(separator="\n")
    data = {}

    # Nom du Pokémon et ID depuis l'URL
    if url:
        name_match = re.search(r"https://unite-db.com/pokemon/([a-z0-9\-]+)", url)
        if name_match:
            data["|-nom"] = name_match.group(1).capitalize()
            data["|-ID"] = name_match.group(1)
        else:
            print(f"❌ Erreur dans l'extraction du nom depuis l'URL : {url}")
            return data
    else:
        data["|-nom"] = "Unknown"
        data["|-ID"] = "unknown"

    # Infos de style, type de dommage et rôle
    data["|-style"] = extract_keyword_after_label(content, "Style")
    data["|-type de dmg"] = extract_keyword_after_label(content, "Damage Type")
    data["|-role"] = extract_keyword_after_label(content, "Role")

    # Evolutions
    evolutions = re.findall(r"Evolves (?:from|into) (\w+)", content)
    data["-Evolutions"] = evolutions

    def extract_stat_series(label):
        """Extraire les séries de statistiques (HP, Attaque, Défense, etc.)."""
        pattern = rf"{label}\s*(?:\n|:)\s*((?:\d+\s*)+)"
        match = re.search(pattern, content)
        return [int(num) for num in match.group(1).split()] if match else []

    data["-ses HP"] = extract_stat_series("HP")
    data["-son atk"] = extract_stat_series("Attack")
    data["-sa def"] = extract_stat_series("Defense")
    data["-son atk spé"] = extract_stat_series("Sp. Atk")
    data["-sa def spé"] = extract_stat_series("Sp. Def")
    data["-son crit rate"] = extract_stat_series("Crit Rate")
    data["-son cooldown reduction"] = extract_stat_series("Cooldown Reduction")
    data["-son life steal"] = extract_stat_series("Lifesteal")

    # Vérification des capacités booléennes
    text = content.lower()
    def has_keywords(*keywords):
        return any(k.lower() in text for k in keywords)

    data["--sa capacité a pouvoir heal les alliers."] = has_keywords("heal ally", "restore hp to allies")
    data["--sa capacité a croud controle"] = has_keywords("stun", "slow", "knockback", "immobilize")
    data["--sa capacité a SE shield"] = has_keywords("gain a shield", "shields itself")
    data["--sa capacité a shield les alliers"] = has_keywords("grants shield", "shield allies")
    data["--sa capacité a buff les alliers"] = has_keywords("increase ally", "buff allies")
    data["--sa capacité a debuff"] = has_keywords("reduce enemy", "lower enemy stats", "debuff")
    data["--sa capacité a dmg basé (en partie ou entièrement) sur hp (adverse)"] = has_keywords("damage based on hp", "target's max hp")
    data["--sa capacité a execute (tuer obligatoirement sous un certain seuille d'hp)"] = has_keywords("execute", "finish off", "kill under")

    # Image
    img = soup.find("img")
    data["---son image"] = img["src"] if img and img.has_attr("src") else ""

    return data


def extract_keyword_after_label(text, label):
    """Extraire une donnée après un label spécifique (Style, Damage Type, etc.)."""
    match = re.search(rf"{label}:\s*(\w+)", text)
    return match.group(1) if match else "Unknown"


def run_scraper_online(main_html, output_json, debug=False):
    """Scraper les données des Pokémon directement depuis les pages en ligne."""
    links = extract_pokemon_links(main_html)
    all_data = []

    print(f"🌍 Scraping {len(links)} Pokémon...")

    for url in links:
        try:
            print(f"🌐 Téléchargement : {url}")
            response = requests.get(url)
            response.raise_for_status()

            # Vérifier que la page contient des données
            if response.status_code == 200:
                print(f"✅ Réponse correcte pour {url}.")
            else:
                print(f"❌ Erreur avec le code de statut {response.status_code} pour {url}")
                continue

            data = extract_data_from_pokemon_page(url)
            all_data.append(data)

            if debug:
                print(f"\n--- Débogage pour {url} ---")
                print(json.dumps(data, indent=2, ensure_ascii=False))

            time.sleep(1)  # Délai pour ne pas surcharger le serveur

        except Exception as e:
            print(f"❌ Erreur sur {url} : {e}")

    with open(output_json, "w", encoding="utf-8") as f:
        json.dump(all_data, f, indent=2, ensure_ascii=False)

    print(f"\n✅ Enregistré {len(all_data)} Pokémon dans {output_json} (online)")

def extract_data_from_pokemon_page(url):
    """Extrait les données d’un Pokémon depuis sa page en ligne (DOM profond explicite)."""
    try:
        response = requests.get(url)
        response.raise_for_status()
    except Exception as e:
        print(f"❌ Erreur HTTP pour {url} : {e}")
        return {}

    soup = BeautifulSoup(response.text, "html.parser")

    article = soup.find("div", class_="character-info")
    if not article:
        print(f"❌ Pas de .character-info trouvé pour {url}")
        return {}

    data = {}

    # Nom et ID
    h1 = article.find("h1")
    data["|-nom"] = h1.text.strip() if h1 else url.split("/")[-1].capitalize()
    data["|-ID"] = url.split("/")[-1]

    # Recherche DOM précise du bloc tags
    tags = article.select_one("div.character-image > div.tags")
    data["|-style"] = "Unknown"
    data["|-type de dmg"] = "Unknown"
    data["|-role"] = "Unknown"
    if tags:
        for pill in tags.find_all("p", class_="pill"):
            classes = pill.get("class", [])
            value = pill.get_text(strip=True)
            if "difficulty" in classes:
                data["|-style"] = value
            elif "range" in classes:
                data["|-type de dmg"] = value
            elif "role" in classes:
                data["|-role"] = value

    # Évolutions
    evolutions = []
    evol_section = soup.select_one("section.evolution")
    if evol_section:
        for p in evol_section.find_all("p"):
            evolutions += re.findall(r"\b[A-Z][a-z]+", p.get_text())
    data["-Evolutions"] = list(set(evolutions))

    # Image
    img_tag = article.find("img")
    data["---son image"] = img_tag["src"] if img_tag and img_tag.has_attr("src") else ""

    # Texte global pour les booléens
    description = article.get_text(separator=" ").lower()

    def has_keywords(*keywords):
        return any(k in description for k in keywords)

    data["--sa capacité a pouvoir heal les alliers."] = has_keywords("heal ally", "restore hp to allies")
    data["--sa capacité a croud controle"] = has_keywords("stun", "slow", "knockback", "immobilize")
    data["--sa capacité a SE shield"] = has_keywords("gain a shield", "shields itself")
    data["--sa capacité a shield les alliers"] = has_keywords("grants shield", "shield allies")
    data["--sa capacité a buff les alliers"] = has_keywords("increase ally", "buff allies")
    data["--sa capacité a debuff"] = has_keywords("reduce enemy", "lower enemy stats", "debuff")
    data["--sa capacité a dmg basé (en partie ou entièrement) sur hp (adverse)"] = has_keywords("damage based on hp", "target's max hp")
    data["--sa capacité a execute (tuer obligatoirement sous un certain seuille d'hp)"] = has_keywords("execute", "finish off", "kill under")

    return data


if __name__ == "__main__":
    # Pour scraper depuis les pages en ligne avec mode débogage
    run_scraper_online(
        main_html="main_page.html",  # Page principale contenant les liens
        output_json="pokemon_data.json",  # Fichier JSON de sortie
        debug=True  # Passer à False pour désactiver le débogage
    )