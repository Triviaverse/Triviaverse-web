const createPasswordResetTokensTable = (db) => {
    return new Promise((resolve, reject) => {
      const query = `
        CREATE TABLE IF NOT EXISTS password_reset_tokens (
          email VARCHAR(255) NOT NULL,
          token VARCHAR(255) NOT NULL,
          created_at TIMESTAMP NULL DEFAULT NULL,
          PRIMARY KEY (email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
      `;
      db.query(query, (err) => {
        if (err) return reject(err);
        console.log(' - Table password_reset_tokens created or already exists.');
        resolve();
      });
    });
  };
  
  export default createPasswordResetTokensTable;
  