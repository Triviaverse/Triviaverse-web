const alterValaszokTable = (db) => {
    return new Promise((resolve, reject) => {
      const checkQuery = `
        SELECT * FROM information_schema.TABLE_CONSTRAINTS 
        WHERE TABLE_NAME = 'valaszok' AND CONSTRAINT_NAME = 'valaszok_kerdes_id_foreign';
      `;
      
      db.query(checkQuery, (err, results) => {
        if (err) return reject(err);
        if (results.length > 0) {
          console.log(' - Constraint valaszok_kerdes_id_foreign already exists, skipping alteration.');
          return resolve();
        }
  
        const alterQuery = `
          ALTER TABLE valaszok
            ADD CONSTRAINT valaszok_kerdes_id_foreign FOREIGN KEY (kerdes_id) REFERENCES kerdesek (id) ON DELETE CASCADE;
        `;
        
        db.query(alterQuery, (err) => {
          if (err) return reject(err);
          console.log(' - Alterations on valaszok table applied.');
          resolve();
        });
      });
    });
  };
  
  export default alterValaszokTable;
  