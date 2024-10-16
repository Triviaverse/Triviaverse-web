const alterKerdesekTable = (db) => {
    return new Promise((resolve, reject) => {
      const checkQuery = `
        SELECT * FROM information_schema.TABLE_CONSTRAINTS 
        WHERE TABLE_NAME = 'kerdesek' AND CONSTRAINT_NAME = 'kerdesek_targy_foreign';
      `;
      
      db.query(checkQuery, (err, results) => {
        if (err) return reject(err);
        if (results.length > 0) {
          console.log(' - Constraint kerdesek_targy_foreign already exists, skipping alteration.');
          return resolve();
        }
  
        const alterQuery = `
          ALTER TABLE kerdesek
            ADD CONSTRAINT kerdesek_targy_foreign FOREIGN KEY (targy) REFERENCES targyak (id) ON DELETE CASCADE;
        `;
        
        db.query(alterQuery, (err) => {
          if (err) return reject(err);
          console.log(' - Alterations on kerdesek table applied.');
          resolve();
        });
      });
    });
  };
  
  export default alterKerdesekTable;
  