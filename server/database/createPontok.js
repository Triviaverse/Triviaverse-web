const createPontokTable = (db) => {
    return new Promise((resolve, reject) => {
      const query = `
        CREATE TABLE IF NOT EXISTS pontok (
          id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
          targy BIGINT(20) UNSIGNED NOT NULL,
          osszpont INT(11) NOT NULL,
          email VARCHAR(255) NOT NULL,
          created_at TIMESTAMP NULL DEFAULT NULL,
          updated_at TIMESTAMP NULL DEFAULT NULL,
          PRIMARY KEY (id),
          KEY pontok_targy_foreign (targy)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
      `;
      db.query(query, (err) => {
        if (err) return reject(err);
        console.log(' - Table pontok created or already exists.');
        resolve();
      });
    });
  };
  
  export default createPontokTable;
  