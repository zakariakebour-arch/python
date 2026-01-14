from flask import Flask, request, jsonify

app = Flask(__name__)

# Almacenamiento en memoria para las notas (simula una base de datos)
notas = []

# Identificador autoincremental para las notas
id_nota = 0


# Endpoint para crear una nueva nota
@app.route("/api/notes", methods=["POST"])
def crear_nota():
    # Validar que el Content-Type sea application/json
    if not request.is_json:
        return jsonify({"error": "Header incorrecto"}), 415

    recepcion = request.get_json(silent=True)

    # Validar que el JSON sea válido
    if not recepcion:
        return jsonify({"error": "Formato JSON inválido"}), 400

    titulo = recepcion.get("titulo")
    contenido = recepcion.get("content")
    importante = recepcion.get("important")

    # Validar existencia de los campos obligatorios
    if titulo is None or contenido is None or importante is None:
        return jsonify({"error": "No pueden existir campos vacíos"}), 400

    # Validar tipos de datos
    if not isinstance(titulo, str) or not isinstance(contenido, str) or not isinstance(importante, bool):
        return jsonify({"error": "Tipo de dato incorrecto"}), 400

    global id_nota

    # Crear la nota
    nota = {
        "id": id_nota,
        "titulo": titulo,
        "contenido": contenido,
        "importante": importante
    }

    # Almacenar la nota en la lista
    notas.append(nota)

    # Incrementar el identificador para la siguiente nota
    id_nota += 1

    # Respuesta de creación exitosa
    return jsonify({"correcto": "La nota se creó correctamente"}), 201


# Endpoint para obtener todas las notas
@app.route("/api/notes", methods=["GET"])
def devolver_notas():
    # Verificar si existen notas registradas
    if not notas:
        return jsonify({"error": "No hay notas disponibles"}), 404

    return jsonify(notas), 200


# Endpoint para obtener una nota por su ID
@app.route("/api/notes/<int:id>", methods=["GET"])
def obtener_nota(id):
    # Buscar la nota por su identificador
    for nota in notas:
        if nota["id"] == id:
            return jsonify(nota), 200

    return jsonify({"error": "Nota no encontrada"}), 404


# Endpoint para actualizar el título y el contenido de una nota
@app.route("/api/notes/<int:id>", methods=["PUT"])
def actualizar(id):
    # Validar que el Content-Type sea application/json
    if not request.is_json:
        return jsonify({"error": "Content-Type inválido"}), 415

    recepcion = request.get_json(silent=True)

    # Validar que el JSON sea válido
    if not recepcion:
        return jsonify({"error": "JSON inválido"}), 400

    titulo_nuevo = recepcion.get("titulo")
    contenido_nuevo = recepcion.get("contenido")

    # Actualizar la nota si existe
    for nota in notas:
        if nota["id"] == id:
            nota["titulo"] = titulo_nuevo
            nota["contenido"] = contenido_nuevo
            return jsonify({"mensaje": "Actualizado correctamente"}), 200

    return jsonify({"error": "Nota no encontrada"}), 404


# Endpoint para marcar una nota como importante
@app.route("/api/notes/<int:id>/importante", methods=["PATCH"])
def marcar_desmarcar(id):
    # Buscar la nota y marcarla como importante
    for nota in notas:
        if nota["id"] == id:
            nota["importante"] = True
            return jsonify({"mensaje": "Nota marcada como importante"}), 200

    return jsonify({"error": "Nota no encontrada"}), 404


# Endpoint para eliminar una nota por su ID
@app.route("/api/notes/<int:id>", methods=["GET"])
def eliminar(id):
    # Eliminar la nota según su ID (no por índice de lista)
    for i, nota in enumerate(notas):
        if nota["id"] == id:
            notas.pop(i)
            return jsonify({"mensaje": "La nota se eliminó correctamente"}), 200

    return jsonify({"error": "No se encontró la nota"}), 404


if __name__ == '__main__':
    app.run(debug=True)
