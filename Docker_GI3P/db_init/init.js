// Seleccionamos o creamos la base de datos
db = db.getSiblingDB('Logs');

// Creamos la colección con la validación de esquema
db.createCollection("registres_connexio", {
   validator: {
      $jsonSchema: {
         bsonType: "object",
         required: [ "url", "metode", "timestamp", "navegador", "ip", "temps_resposta_ms" ],
         properties: {
            url: { bsonType: "string" },
            metode: { bsonType: "string", enum: ["GET", "POST", "PUT", "DELETE", "PATCH", "OPTIONS"] },
            usuari_id: { bsonType: ["objectId", "string", "null"] },
            timestamp: { bsonType: "date" },
            navegador: { bsonType: "string" },
            ip: { bsonType: "string" },
            temps_resposta_ms: { bsonType: ["int", "double", "long"] }
         }
      }
   }
});

print("Base de dades MongoDB inicialitzada amb èxit i col·lecció 'registres_connexio' creada amb validació de esquema.");