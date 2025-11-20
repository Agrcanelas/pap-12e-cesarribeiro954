<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Carrinho - Ecopeças</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<style>
    * { box-sizing: border-box; margin:0; padding:0; font-family:'Roboto', sans-serif; }

    body {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 50px 20px;
        /* Substitua o URL abaixo pela sua imagem de fundo desejada */
        background: linear-gradient(rgba(0,128,0,0.3), rgba(0,128,0,0.3)),
                    url('https://images.unsplash.com/photo-1605902711622-cfb43c4430d8?auto=format&fit=crop&w=1350&q=80') no-repeat center center/cover;
        background-size: cover;
        backdrop-filter: blur(2px);
    }

    .cart-container {
        background: rgba(255,255,255,0.95);
        width: 100%;
        max-width: 700px;
        border-radius: 25px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.25);
        padding: 40px;
        margin-bottom: 50px;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .cart-container:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 50px rgba(0,0,0,0.3);
    }

    h1 { text-align:center; margin-bottom:40px; color:#2e7d32; font-size:34px; letter-spacing:1px; }

    .cart-item {
        display:flex;
        justify-content:space-between;
        align-items:center;
        padding:20px 20px;
        border-radius:15px;
        margin-bottom:20px;
        background:#f9fff9;
        box-shadow:0 6px 20px rgba(0,0,0,0.08);
        transition: transform 0.3s, box-shadow 0.3s, background 0.3s;
    }

    .cart-item:hover {
        transform: translateY(-5px);
        box-shadow:0 12px 30px rgba(0,0,0,0.15);
        background:#e0ffe5;
    }

    .item-info h3 { margin-bottom:8px; color:#333; font-size:18px; }
    .item-info p { color:#555; font-size:14px; }

    .item-quantity { display:flex; align-items:center; gap:10px; }

    .item-quantity input {
        width:60px;
        padding:8px;
        text-align:center;
        border-radius:10px;
        border:1px solid #ccc;
        transition:border 0.3s, box-shadow 0.3s;
    }

    .item-quantity input:focus {
        border-color:#66d78b;
        box-shadow:0 0 10px rgba(102,215,139,0.5);
    }

    .remove-btn {
        background: linear-gradient(135deg, #ff6b6b, #ff3d3d);
        border:none;
        color:#fff;
        padding:10px 18px;
        border-radius:50px;
        cursor:pointer;
        font-weight:bold;
        transition: all 0.3s;
    }

    .remove-btn:hover {
        transform: scale(1.1);
        box-shadow:0 6px 20px rgba(255,61,61,0.4);
    }

    .cart-total {
        text-align:right;
        margin-top:25px;
        font-size:24px;
        font-weight:bold;
        color:#2e7d32;
        transition: transform 0.3s ease;
    }

    .checkout-btn {
        margin-top:20px;
        width:100%;
        padding:16px;
        background: linear-gradient(135deg, #66d78b, #4caf70);
        color:#fff;
        border:none;
        border-radius:50px;
        font-size:18px;
        font-weight:bold;
        cursor:pointer;
        transition: all 0.3s ease;
        box-shadow:0 6px 20px rgba(76,175,112,0.3);
    }

    .checkout-btn:hover {
        transform: scale(1.03);
        box-shadow:0 10px 30px rgba(76,175,112,0.4);
    }

    /* Responsivo */
    @media (max-width:600px){
        .cart-item{ flex-direction: column; align-items:flex-start; gap:15px; }
        .item-quantity{ justify-content:flex-start; }
        .cart-total{ text-align:left; }
    }
</style>
</head>
<body>

<div class="cart-container">
    <h1>Carrinho</h1>

    <div class="cart-item">
        <div class="item-info">
            <h3>Filtro de Óleo</h3>
            <p>Preço: R$ 50,00</p>
        </div>
        <div class="item-quantity">
            <input type="number" value="1" min="1">
        </div>
        <button class="remove-btn">Remover</button>
    </div>

    <div class="cart-item">
        <div class="item-info">
            <h3>Pastilhas de Freio</h3>
            <p>Preço: R$ 120,00</p>
        </div>
        <div class="item-quantity">
            <input type="number" value="2" min="1">
        </div>
        <button class="remove-btn">Remover</button>
    </div>

    <div class="cart-total">Total: R$ 290,00</div>
    <button class="checkout-btn">Finalizar Compra</button>
</div>

<script>
    const cartItems = document.querySelectorAll('.cart-item');
    const totalEl = document.querySelector('.cart-total');

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.cart-item').forEach(item => {
            const priceText = item.querySelector('.item-info p').innerText;
            const price = parseFloat(priceText.replace('R$ ', '').replace(',', '.'));
            const quantity = item.querySelector('.item-quantity input').value;
            total += price * quantity;
        });
        totalEl.style.transform = 'scale(1.2)';
        totalEl.innerText = 'Total: R$ ' + total.toFixed(2).replace('.', ',');
        setTimeout(()=>{ totalEl.style.transform='scale(1)'; },200);
    }

    cartItems.forEach(item => {
        const qtyInput = item.querySelector('.item-quantity input');
        const removeBtn = item.querySelector('.remove-btn');

        qtyInput.addEventListener('input', updateTotal);
        removeBtn.addEventListener('click', () => {
            item.remove();
            updateTotal();
        });
    });

    updateTotal();
</script>

</body>
</html>

