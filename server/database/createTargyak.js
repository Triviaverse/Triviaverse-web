const createTargyakTable = (db) => {
    return new Promise((resolve, reject) => {
      const query = `
        CREATE TABLE IF NOT EXISTS targyak (
          id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
          targyneve VARCHAR(255) NOT NULL,
          created_at TIMESTAMP NULL DEFAULT NULL,
          updated_at TIMESTAMP NULL DEFAULT NULL,
          PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
      `;
      db.query(query, (err) => {
        if (err) return reject(err);
        console.log(' - Table targyak created or already exists.');
        resolve();
      });
    });
  };
  
  export default createTargyakTable;
  