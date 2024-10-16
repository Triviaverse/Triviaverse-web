const alterPontokTable = (db) => {
    return new Promise((resolve, reject) => {
      const checkQuery = `
        SELECT * FROM information_schema.TABLE_CONSTRAINTS 
        WHERE TABLE_NAME = 'pontok' AND CONSTRAINT_NAME = 'pontok_targy_foreign';
      `;
      
      db.query(checkQuery, (err, results) => {
        if (err) return reject(err);
        if (results.length > 0) {
          console.log(' - Constraint pontok_targy_foreign already exists, skipping alteration.');
          return resolve();
        }
  
        const alterQuery = `
          ALTER TABLE pontok
            ADD CONSTRAINT pontok_targy_foreign FOREIGN KEY (targy) REFERENCES targyak (id) ON DELETE CASCADE;
        `;
        
        db.query(alterQuery, (err) => {
          if (err) return reject(err);
          console.log(' - Alterations on pontok table applied.');
          resolve();
        });
      });
    });
  };
  
  export default alterPontokTable;
  