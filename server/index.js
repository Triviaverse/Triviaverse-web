import express from 'express';
import mysql from 'mysql';
import { readFileSync, writeFileSync } from 'fs';
import { resolve } from 'path';
import fs from 'fs';

const app = express();
app.use(express.json());

const defaultConfig = {
  db: {
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'triviaverse',
  },
};

const configPath = resolve('config.json');

let config;
try {
  config = JSON.parse(readFileSync(configPath, 'utf-8'));
} catch (error) {
  console.error('\x1b[31m%s\x1b[0m', 'Error: config.json not found. Generating with default settings.');
  writeFileSync(configPath, JSON.stringify(defaultConfig, null, 2));
  config = defaultConfig;
}

const db = mysql.createPool({
  host: config.db.host,
  user: config.db.user,
  password: config.db.password,
  database: config.db.database,
});

// Enhanced error logging function
const logError = (error) => {
  console.error('\x1b[31m%s\x1b[0m', 'Error occurred:', error.message);
  console.error('Stack trace:', error.stack);
};

// Check if the database exists
const checkDatabaseExists = () => {
  return new Promise((resolve, reject) => {
    db.query(`SHOW DATABASES LIKE '${config.db.database}'`, (err, results) => {
      if (err) return reject(err);
      resolve(results.length > 0);
    });
  });
};

// Create the database
const createDatabase = () => {
  return new Promise((resolve, reject) => {
    db.query(`CREATE DATABASE ${config.db.database}`, (err) => {
      if (err) return reject(err);
      console.log(`Database '${config.db.database}' created successfully.`);
      resolve();
    });
  });
};

// Load and execute all database files
const loadDatabaseFiles = async (type) => {
  const files = fs.readdirSync('./server/database');
  const importPromises = files
    .filter(file => file.startsWith(type))
    .map(async (file) => {
      const { default: execute } = await import(`./database/${file}`);
      return execute(db);
    });

  return Promise.all(importPromises);
};

// Main database setup call
const setupDatabase = async () => {
  try {
    const exists = await checkDatabaseExists();
    if (!exists) {
      await createDatabase();
    } else {
      console.log(`Database '${config.db.database}' already exists.`);
    }

    const start = Date.now();
    try {
      // First load and execute CREATE files
      await loadDatabaseFiles('create');
      console.log('All create operations completed.');

      // Then load and execute ALTER files
      await loadDatabaseFiles('alter');
      console.log('All alter operations completed.');

      const duration = Date.now() - start;
      console.log(`Total time taken: ${duration}ms`);
    } catch (err) {
      logError(err);
    }
  } catch (error) {
    logError(error);
  }
};

setupDatabase();

// Middleware for logging request/response time
app.use((req, res, next) => {
  const start = Date.now();
  res.on('finish', () => {
    const duration = Date.now() - start;
    console.log(`Route: ${req.method} ${req.originalUrl} - Time: ${duration}ms`);
  });
  next();
});

// Example API route with enhanced error logging
app.get('/data', (req, res) => {
  db.query('SELECT * FROM yourTable', (err, results) => {
    if (err) {
      logError(err);
      return res.status(500).json({ error: err.message });
    }
    res.json(results);
  });
});

// Start the server
const port = 3001;
app.listen(port, () => {
  console.log(`Backend server is running on http://localhost:${port}`);
});
