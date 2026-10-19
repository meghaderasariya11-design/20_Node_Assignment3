CALL PHP REST API FROM EXPRESS
==============================

1. Start Apache and MySQL in XAMPP.
2. Start this Express server:
   cd express_api
   npm install
   npm start
3. Open in browser:
   http://localhost:3000/call-php-categories
4. The Express route fetches JSON from PHP API:
   http://localhost/php_practical_assignment/php_api/categories.php

For a simple standalone example, use this server.js instead of the main server.js,
or copy the route below into server.js:

const express = require('express');
const app = express();

app.get('/call-php-categories', async (req, res) => {
  try {
    const response = await fetch('http://localhost/php_practical_assignment/php_api/categories.php');
    const data = await response.json();
    res.json(data);
  } catch (error) {
    res.status(500).json({ error: 'Could not call PHP API. Check Apache and the URL.' });
  }
});

app.listen(3000, () => console.log('Server running on port 3000'));

IMPORTANT: The folder URL is based on the folder name in htdocs. If you use a different
folder name, update the URL in this route.
