import express from 'express';
import mysql from 'mysql'; // or your database driver
import { readFileSync } from 'fs';
import { resolve } from 'path';

// Create an Express app
const app = express();

// Middleware to parse JSON requests
app.use(express.json());

// Load DB config from config.json
const configPath = resolve('config.json');
const config = JSON.parse(readFileSync(configPath, 'utf-8'));

// Create a MySQL connection pool
const db = mysql.createPool({
  host: config.db.host,
  user: config.db.user,
  password: config.db.password,
  database: config.db.database,
});

// Define an API route
app.get('/data', (req, res) => {
  db.query('SELECT * FROM yourTable', (err, results) => {
    if (err) {
      res.status(500).json({ error: err.message });
    } else {
      res.json(results);
    }
  });
});

// Start the server
const port = 3001; // This can be set to any port other than 3000
app.listen(port, () => {
  console.log(`Backend server is running on http://localhost:${port}`);
});
