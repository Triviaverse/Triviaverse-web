const createKerdesekTable = (db) => {
    return new Promise((resolve, reject) => {
      const query = `
        CREATE TABLE IF NOT EXISTS kerdesek (
          id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
          kerdes TEXT NOT NULL,
          targy BIGINT(20) UNSIGNED NOT NULL,
          created_at TIMESTAMP NULL DEFAULT NULL,
          updated_at TIMESTAMP NULL DEFAULT NULL,
          PRIMARY KEY (id),
          KEY kerdesek_targy_foreign (targy)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
      `;
      db.query(query, (err) => {
        if (err) return reject(err);
        console.log(' - Table kerdesek created or already exists.');
        resolve();
      });
    });
  };
  
  export default createKerdesekTable;
  