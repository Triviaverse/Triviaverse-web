import express from 'express';
import mysql from 'mysql';
import { readFileSync } from 'fs';
import { resolve } from 'path';

const app = express();

app.use(express.json());

const configPath = resolve('config.json');
const config = JSON.parse(readFileSync(configPath, 'utf-8'));

const db = mysql.createPool({
  host: config.db.host,
  user: config.db.user,
  password: config.db.password,
  database: config.db.database,
});

// Define an API route
app.get('/api/data', (req, res) => {
  db.query('SELECT * FROM yourTable', (err, results) => {
    if (err) {
      res.status(500).send({ error: err.message });
    } else {
      res.json(results);
    }
  });
});

// Export the app for Vite
export const viteNodeApp = app;
