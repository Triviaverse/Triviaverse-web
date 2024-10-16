const createUsersTable = (db) => {
    return new Promise((resolve, reject) => {
      const query = `
        CREATE TABLE IF NOT EXISTS users (
          id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
          name VARCHAR(255) NOT NULL,
          email VARCHAR(255) NOT NULL,
          email_verified_at TIMESTAMP NULL DEFAULT NULL,
          password VARCHAR(255) NOT NULL,
          remember_token VARCHAR(100) DEFAULT NULL,
          created_at TIMESTAMP NULL DEFAULT NULL,
          updated_at TIMESTAMP NULL DEFAULT NULL,
          PRIMARY KEY (id),
          UNIQUE KEY users_email_unique (email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
      `;
      db.query(query, (err) => {
        if (err) return reject(err);
        console.log(' - Table users created or already exists.');
        resolve();
      });
    });
  };
  
  export default createUsersTable;
  