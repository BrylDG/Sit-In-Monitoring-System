import mysql from 'mysql2/promise';
import express, { Request, Response } from 'express';

const app: express.Application = express();
app.use(express.json());

// ✅ Database Configuration
const dbConfig = {
    host: 'localhost',
    user: 'root', // Replace with actual MySQL username
    password: '', // Replace with actual MySQL password
    database: 'sitinmanagementsystem'
};

// ✅ Connection Pool for Better Performance
const pool = mysql.createPool(dbConfig);

// ✅ Verify User Function
export const verifyUser = async (username: string, password: string): Promise<boolean> => {
    try {
        const connection = await pool.getConnection();
        const [rows]: any = await connection.execute(
            'SELECT * FROM users WHERE username = ? AND password = ?',
            [username, password]
        );
        connection.release();
        return rows.length > 0;
    } catch (error) {
        console.error('Error verifying user:', error);
        throw error;
    }
};