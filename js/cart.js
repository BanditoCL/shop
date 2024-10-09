// Array para almacenar los productos en el carrito
let cart = [];
let total = 0;

// Función para actualizar el carrito
function updateCart() {
    $('#cart-count').text(cart.length);
    $('#cart-items').empty();
    total = 0;
    cart.forEach((item, index) => {
        $('#cart-items').append(`
    <li class="list-group-item d-flex justify-content-between align-items-center">
        ${item.name} - $${item.price.toFixed(2)}
        <button class="btn btn-danger btn-sm" onclick="removeFromCart(${index})">Quitar</button>
    </li>
`);
        total += item.price;
    });
    $('#cart-total').text(total.toFixed(2)); // Actualiza el total
}

// Función para agregar un producto al carrito
function addToCart(itemId, itemName, itemPrice) {
    cart.push({
        id: itemId,
        name: itemName,
        price: itemPrice
    });
    updateCart();
}

// Función para quitar un producto del carrito
function removeFromCart(index) {
    total -= cart[index].price; // Resta el precio del producto que se va a eliminar
    cart.splice(index, 1); // Elimina el producto del carrito
    updateCart();
}

$(document).ready(function() {
    updateCart(); // Inicializa el contador y el total
});