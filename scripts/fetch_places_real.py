import requests
import json
import time
import re

API_KEY = "AIzaSyBXlOSHGgRopmgs9pk--FQ6XViAamMaUis"
BASE_URL = "https://maps.googleapis.com/maps/api/place/textsearch/json"
DETAILS_URL = "https://maps.googleapis.com/maps/api/place/details/json"

CATEGORIES = [
    'Restaurante', 'Pizzaria', 'Farmácia', 'Supermercado', 'Academia',
    'Oficina Mecânica', 'Escola', 'Hotel', 'Clínica Médica', 'Salão de Beleza',
    'Loja de Roupas', 'Construtora', 'Advogado', 'Padaria'
]

# Database ID trackers
cat_id_counter = 1
neigh_id_counter = 1
company_id_counter = 1

categories_map = {} # Name -> ID
neighborhoods_map = {} # Name -> ID

sql_statements = []

def slugify(text):
    text = text.lower()
    text = re.sub(r'[^\w\s-]', '', text)
    text = re.sub(r'[\s_-]+', '-', text)
    text = re.sub(r'^-+|-+$', '', text)
    return text

def get_neighborhood_from_address(address):
    # Try to parse "Bairro" from standard format: "Rua X, 123 - Bairro, Curitiba"
    parts = address.split('-')
    if len(parts) > 1:
        subparts = parts[-1].split(',')
        if len(subparts) >= 2:
            return subparts[0].strip() # The part before the city
    return "Centro" # Fallback

def fetch_places(category):
    print(f"Fetching {category}...")
    query = f"{category} em Curitiba, PR"
    params = {
        'query': query,
        'key': API_KEY,
        'language': 'pt-BR',
        'region': 'br'
    }

    results = []
    response = requests.get(BASE_URL, params=params)
    data = response.json()

    if 'results' in data:
        results.extend(data['results'][:5]) # Limit to 5 per category to be fast but realistic (70 total)

    return results

def get_details(place_id):
    params = {
        'place_id': place_id,
        'fields': 'formatted_phone_number,website',
        'key': API_KEY
    }
    response = requests.get(DETAILS_URL, params=params)
    return response.json().get('result', {})

# Header
sql_statements.append("SET FOREIGN_KEY_CHECKS=0;")
sql_statements.append("TRUNCATE TABLE companies;")
sql_statements.append("TRUNCATE TABLE categories;")
sql_statements.append("TRUNCATE TABLE neighborhoods;")
sql_statements.append("SET FOREIGN_KEY_CHECKS=1;\n")

# Process Categories
sql_statements.append("-- Categories")
for cat in CATEGORIES:
    slug = slugify(cat)
    sql_statements.append(f"INSERT INTO categories (id, name, slug) VALUES ({cat_id_counter}, '{cat}', '{slug}');")
    categories_map[cat] = cat_id_counter
    cat_id_counter += 1
sql_statements.append("")

# Process Companies
sql_statements.append("-- Companies & Neighborhoods")

for cat in CATEGORIES:
    places = fetch_places(cat)

    for place in places:
        name = place.get('name', 'N/A').replace("'", "''")
        address = place.get('formatted_address', '').replace("'", "''")
        rating = place.get('rating', 0)
        user_ratings_total = place.get('user_ratings_total', 0)
        place_id = place.get('place_id')
        lat = place['geometry']['location']['lat']
        lng = place['geometry']['location']['lng']

        # Get Neighborhood
        neigh_name = get_neighborhood_from_address(address)
        if neigh_name not in neighborhoods_map:
            neigh_slug = slugify(neigh_name)
            sql_statements.append(f"INSERT INTO neighborhoods (id, name, slug, city) VALUES ({neigh_id_counter}, '{neigh_name}', '{neigh_slug}', 'Curitiba');")
            neighborhoods_map[neigh_name] = neigh_id_counter
            neigh_id_counter += 1

        neigh_id = neighborhoods_map[neigh_name]
        cat_id = categories_map[cat]

        # Fetch Details (Phone)
        details = get_details(place_id)
        phone = details.get('formatted_phone_number', '')
        website = details.get('website', '')

        # Clean phone for Whatsapp (remove non-digits)
        whatsapp = re.sub(r'\D', '', phone)
        if whatsapp:
            whatsapp = '55' + whatsapp # Add Brazil country code if not present, though usually is. Assuming local format.

        slug = slugify(f"{name}-{neigh_name}")
        description = f"Uma das melhores opções de {cat} em {neigh_name}. Avaliação média: {rating} ({user_ratings_total} avaliações)."

        sql = f"INSERT INTO companies (category_id, neighborhood_id, name, slug, description, address, phone, whatsapp, latitude, longitude, website, image_url, status) VALUES "
        sql += f"({cat_id}, {neigh_id}, '{name}', '{slug}', '{description}', '{address}', '{phone}', '{whatsapp}', {lat}, {lng}, '{website}', '/img_exemplo.png', 'active');"

        sql_statements.append(sql)
        time.sleep(0.1) # Be nice to API

# Output
with open("ogm/import_data.sql", "w", encoding="utf-8") as f:
    f.write("\n".join(sql_statements))

print("Done! Generated ogm/import_data.sql")
