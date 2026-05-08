db.createCollection("registres_connexio", {
   validator: {
      $jsonSchema: {
         bsonType: "object",
         required: [ "url", "metode", "timestamp", "navegador", "ip", "temps_resposta_ms" ],
         properties: {
            url: {
               bsonType: "string",
               description: "Ha de ser un string i és obligatori"
            },
            metode: {
               bsonType: "string",
               enum: ["GET", "POST", "PUT", "DELETE", "PATCH", "OPTIONS"],
               description: "Ha de ser un string mètode HTTP vàlid i és obligatori"
            },
            usuari_id: {
               bsonType: ["objectId", "string", "null"],
               description: "Ha de ser l'ID de l'usuari o nul si és anònim"
            },
            timestamp: {
               bsonType: "date",
               description: "Ha de ser una data ISO i és obligatori"
            },
            navegador: {
               bsonType: "string",
               description: "Ha de ser un string i és obligatori"
            },
            ip: {
               bsonType: "string",
               description: "Ha de ser un string (adreça IP) i és obligatori"
            },
            temps_resposta_ms: {
               bsonType: ["int", "double", "long"],
               description: "Ha de ser un valor numèric representant mil·lisegons i és obligatori"
            }
         }
      }
   }
})