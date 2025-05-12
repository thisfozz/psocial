import express from 'express';
import axios from 'axios';
import dotenv from 'dotenv';

dotenv.config();

const app = express();

app.use(express.json());

app.use((req, res, next) => {
    res.header('Access-Control-Allow-Origin', '*');
    res.header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
    res.header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS');
    if (req.method === 'OPTIONS') {
        return res.sendStatus(200);
    }
    next();
});

const API_KEY = process.env.NINJAS_API_KEY;

app.get('/api/password', async (req, res) => {
    const length = req.query.length || 16;
    console.log('Generating password with length:', length);
    console.log('Using API key:', API_KEY ? 'Present' : 'Missing');
    
    try {
        const response = await axios.get(`https://api.api-ninjas.com/v1/passwordgenerator?length=${length}`, {
            headers: {
                'X-Api-Key': API_KEY
            }
        });
        res.json(response.data);
    } catch (error) {
        console.error('Error:', error);
        res.status(500).json({ error: 'Failed to generate password' });
    }
});

app.listen(3001, '0.0.0.0', () => {
    console.log('Password generator listening on port 3001');
});