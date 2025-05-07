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

const API_KEY = process.env.YANDEX_API_KEY;
const FOLDER_ID = process.env.YANDEX_FOLDER_ID;

app.post('/api/translate', async (req, res) => {
    const { text, to = 'ru' } = req.body;

    try {
        const response = await axios.post(
            'https://translate.api.cloud.yandex.net/translate/v2/translate',
            {
                folderId: FOLDER_ID,
                texts: [text],
                targetLanguageCode: to
            },
            {
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Api-Key ${API_KEY}`
                }
            }
        );
        res.json(response.data.translations[0]);
    } catch (error) {
        console.error('Yandex error:', error.response ? error.response.data : error.message);
        res.status(500).json({ error: 'Translation failed', details: error.message, yandex: error.response ? error.response.data : null });
    }
});

app.listen(3000, '0.0.0.0', () => {
    console.log('Proxy listening on port 3000');
}); 