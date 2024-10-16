const createValaszokTable = (db) => {
    return new Promise((resolve, reject) => {
      const query = `
        CREATE TABLE IF NOT EXISTS valaszok (
          id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
          kerdes_id BIGINT(20) UNSIGNED NOT NULL,
          valasz TEXT NOT NULL,
          helyes TINYINT(1) NOT NULL,
          created_at TIMESTAMP NULL DEFAULT NULL,
          updated_at TIMESTAMP NULL DEFAULT NULL,
          PRIMARY KEY (id),
          KEY valaszok_kerdes_id_foreign (kerdes_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
      `;
      db.query(query, (err) => {
        if (err) return reject(err);
        console.log(' - Table valaszok created or already exists.');
        resolve();
      });
    });
  };
  
  export default createValaszokTable;
  