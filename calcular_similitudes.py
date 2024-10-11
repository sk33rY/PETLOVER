import sys
import json
from math import radians, cos, sin, sqrt, atan2

def calcular_similitud_descripcion(desc1, desc2):
    palabras1 = set(word for word in desc1.lower().split() if len(word) > 3)
    palabras2 = set(word for word in desc2.lower().split() if len(word) > 3)
    coincidencias = palabras1.intersection(palabras2)
    total_palabras = len(palabras1.union(palabras2))
    if total_palabras == 0:
        return 0, []
    porcentaje_coincidencia = len(coincidencias) / total_palabras * 100
    return porcentaje_coincidencia, list(coincidencias)

def calcular_distancia(lat1, lng1, lat2, lng2):
    # Convertir de grados a radianes
    lat1, lng1, lat2, lng2 = map(radians, [lat1, lng1, lat2, lng2])
    
    # Aplicar la fórmula de Haversine
    dlat = lat2 - lat1
    dlng = lng2 - lng1
    a = sin(dlat / 2)*2 + cos(lat1) * cos(lat2) * sin(dlng / 2)*2
    c = 2 * atan2(sqrt(a), sqrt(1 - a))
    distancia = 6371 * c  # 6371 es el radio de la Tierra en kilómetros
    return distancia

def calcular_similitud(mascota_perdida, mascota_encontrada):
    peso_raza = 0.2
    peso_tamano = 0.2
    peso_color = 0.2
    peso_descripcion = 0.2
    peso_ubicacion = 0.2

    similitud_raza = 100 if mascota_perdida['raza'] == mascota_encontrada['raza'] else 0
    similitud_tamano = 100 if mascota_perdida['tamano'] == mascota_encontrada['tamano'] else 0
    similitud_color = 100 if mascota_perdida['color'] == mascota_encontrada['color'] else 0
    
    similitud_descripcion, palabras_comunes = calcular_similitud_descripcion(mascota_perdida['descripcion'], mascota_encontrada['descripcion'])
    distancia_km = calcular_distancia(mascota_perdida['lat'], mascota_perdida['lng'], mascota_encontrada['lat'], mascota_encontrada['lng'])
    similitud_ubicacion = max(0, 100 - distancia_km * 10)

    similitud_total = (similitud_raza * peso_raza +
                       similitud_tamano * peso_tamano +
                       similitud_color * peso_color +
                       similitud_descripcion * peso_descripcion +
                       similitud_ubicacion * peso_ubicacion)

    coincidencias = []
    if similitud_raza > 0:
        coincidencias.append(f"Raza: {mascota_encontrada['raza']} (Coincidencia: {similitud_raza}%)")
    if similitud_tamano > 0:
        coincidencias.append(f"Tamaño: {mascota_encontrada['tamano']} (Coincidencia: {similitud_tamano}%)")
    if similitud_color > 0:
        coincidencias.append(f"Color: {mascota_encontrada['color']} (Coincidencia: {similitud_color}%)")
    if similitud_descripcion > 0:
        coincidencias.append(f"Descripción | palabras coincidentes: {', '.join(palabras_comunes)} (Coincidencia: {similitud_descripcion:.2f}%)")
    if distancia_km > 0:
        coincidencias.append(f"Distancia: {distancia_km:.2f} km")
    if similitud_ubicacion > 0:
        coincidencias.append(f"Ubicación: {similitud_ubicacion:.2f}% de proximidad")

    # Asegurarse de devolver la distancia en kilómetros en el resultado
    return {
        'data': mascota_encontrada,
        'coincidencias': coincidencias,
        'similaridad': similitud_total,
        'distancia_km': distancia_km  # Asegurarse de devolver la distancia
    }

if _name_ == "_main_":
    # Leer los datos desde el archivo pasado como argumento
    with open(sys.argv[1], 'r') as file:
        data = json.load(file)

    reporte = data['reporte']
    reportes_encontrados = data['reportes_encontrados']

    resultados = [calcular_similitud(reporte, encontrado) for encontrado in reportes_encontrados]
    resultados = sorted(resultados, key=lambda x: x['similaridad'], reverse=True)

    print(json.dumps(resultados))