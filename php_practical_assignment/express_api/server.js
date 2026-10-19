const express = require('express');
const app = express();
app.use(express.json());

// PHP can call this endpoint using cURL.
app.get('/api/products', (req, res) => {
  res.json([
    { id: 1, pname: 'T-Shirt', price: 499 },
    { id: 2, pname: 'Mobile Phone', price: 12999 },
    { id: 3, pname: 'Laptop', price: 45999 }
  ]);
});

// Call the PHP REST API from Express and return its JSON response.
app.get('/call-php-categories', async (req, res) => {
  try {
    const response = await fetch('http://localhost/php_practical_assignment/php_api/categories.php');
    const data = await response.json();
    res.json(data);
  } catch (error) {
    res.status(500).json({ error: 'Could not call PHP API. Check Apache, PHP URL, and MySQL.' });
  }
});

// PHP can POST JSON here too, if needed.
app.post('/api/products', (req, res) => {
  res.status(201).json({ message: 'Product received from client', product: req.body });
});

const PORT = 3000;
app.listen(PORT, () => console.log(`Express API running at http://localhost:${PORT}`));
