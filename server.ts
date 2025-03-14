import express, { Request, Response } from 'express';
import mysql from 'mysql2/promise';
import cors from 'cors';
import dotenv from 'dotenv';

dotenv.config();

const app = express();
app.use(cors());
app.use(express.json());

const dbConfig = {
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'sitinmanagementsystem'
};

const pool = mysql.createPool(dbConfig);

app.post('/login', async (req, res) => {
    const { username, password } = req.body;

    try {
        const connection = await mysql.createConnection(dbConfig);
        const [rows, fields]: [any, any] = await connection.execute(
            'SELECT * FROM users WHERE username = ? AND password = ?',
            [username, password]
        );
        connection.end();

        if (rows.length > 0) {
            res.json({ success: true, message: 'Login successful' });
        } else {
            res.status(401).json({ success: false, message: 'Invalid credentials' });
        }
    } catch (error) {
        console.error('Database error:', error);
        res.status(500).json({ success: false, message: 'Internal server error' });
    }
});

// ✅ Fix: Ensure the function returns `void`
app.post('/register', async (req: Request, res: Response): Promise<void> => {
    const { idNo, firstName, lastName, middleName, course, yearLevel, emailAddress, username, password } = req.body;
    console.log(req.body)
    // ✅ Check Required Fields
    if (!idNo || !firstName || !lastName || !emailAddress || !username || !password) {
        res.status(400).json({ success: false, message: "All fields are required" });
        return;
    }

    try {
        const connection = await pool.getConnection();

        // ✅ Check for Existing User
        const [existingUsers]: any = await connection.execute(
            'SELECT * FROM users WHERE username = ? OR emailAddress = ?',
            [username, emailAddress]
        );

        if (existingUsers.length > 0) {
            connection.release();
            res.status(409).json({ success: false, message: "Username or emailAddress already exists" });
            return;
        }
        console.log('nakaabot')
        // ✅ Insert User into Database
        await connection.execute(
            `INSERT INTO users (idNo, firstName, lastName, middleName, course, yearLevel, emailAddress, username, password) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)`,
            [idNo, firstName, lastName, middleName, course, yearLevel, emailAddress, username, password]
        );

        connection.release();
        res.status(201).json({ success: true, message: "Registration successful" });
    } catch (error) {
        console.error('Database error:', error);
        res.status(500).json({ success: false, message: "Internal server error" });
    }
});



app.listen(5000, () => {
    console.log('Server running on http://localhost:5000');
});
