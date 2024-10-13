// Función para actualizar el contador del carrito
function updateCartCount() {
  $.ajax({
    url: "get_cart_count.php", // Archivo PHP que obtiene la cantidad de productos en el carrito
    type: "GET",
    success: function (count) {
      $("#cart-count").text(count);
    },
  });
}

// Llamar a la función para actualizar el contador al cargar la página
$(document).ready(function () {
  updateCartCount();
});

// Función para agregar producto al carrito y actualizar el contador
function addToCart(id_producto, descripcion, precio) {
  // Verificar si el usuario ha iniciado sesión
  $.ajax({
    url: "check_session.php", // Archivo PHP para verificar si el usuario está logueado
    type: "GET",
    success: function (userId) {
      if (!userId) {
        alert(
          "Debes iniciar sesión o crear una cuenta para agregar productos al carrito."
        );
        return;
      }

      // Si el usuario está logueado, agregar el producto al carrito
      $.ajax({
        url: "add_to_cart.php", // Archivo PHP para manejar la inserción en la base de datos
        type: "POST",
        data: {
          id_cliente: userId,
          id_producto: id_producto,
          descripcion: descripcion,
          precio: precio,
        },
        success: function (response) {
          if (response == "success") {
            alert("Producto agregado al carrito");
            updateCartCount(); // Actualizar el contador del carrito
          } else {
            alert("Hubo un error al agregar el producto al carrito.");
          }
        },
        error: function () {
          alert("Error en la solicitud AJAX.");
        },
      });
    },
  });
}

// Función para cargar los ítems del carrito en el modal
function loadCartItems() {
  $.ajax({
    url: "get_cart_items.php", // Archivo PHP que obtiene los ítems del carrito
    type: "GET",
    success: function (response) {
      var cartItems = JSON.parse(response); // Parsear el JSON recibido
      var cartList = $("#cart-items");
      cartList.empty(); // Limpiar la lista de ítems en el modal

      var total = 0;

      // Iterar sobre los productos y agregarlos al modal
      cartItems.forEach(function (item) {
        cartList.append(
          '<li class="list-group-item">' +
            item.descripcion +
            " - s/." +
            item.precio +
            ".00 (Cantidad: " +
            item.cantidad +
            ")</li>"
        );
        total += item.precio * item.cantidad;
      });

      // Actualizar el total en el modal
      $("#cart-total").text(total.toFixed(2));
    },
  });
}

// Llamar a la función para cargar los ítems del carrito cuando se hace clic en el ícono del carrito
$("#cart-icon").on("click", function () {
  loadCartItems(); // Cargar los ítems en el modal
});

function loadCartItems() {
  $.ajax({
    url: "get_cart_items.php",
    type: "GET",
    success: function (response) {
      var cartItems = JSON.parse(response);
      var cartList = $("#cart-items");
      cartList.empty();

      var total = 0;

      cartItems.forEach(function (item) {
        cartList.append(`
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        ${item.descripcion} - s/.${item.precio}.00 (Cantidad: ${item.cantidad})
                        <div>
                            <button class="btn btn-sm btn-warning" onclick="decreaseQuantity(${item.id_producto})">-</button>
                            <button class="btn btn-sm btn-success" onclick="increaseQuantity(${item.id_producto})">+</button>
                            <button class="btn btn-sm btn-danger" onclick="removeFromCart(${item.id_producto})">Eliminar</button>
                        </div>
                    </li>
                `);
        total += item.precio * item.cantidad;
      });

      $("#cart-total").text(total.toFixed(2));
    },
  });
}

// Función para disminuir la cantidad de un producto en el carrito
function decreaseQuantity(id_producto) {
  $.ajax({
    url: "decrease_quantity.php", // Archivo PHP para disminuir la cantidad
    type: "POST",
    data: { id_producto: id_producto },
    success: function (response) {
      if (response == "success") {
        loadCartItems(); // Volver a cargar los ítems en el modal
        updateCartCount(); // Actualizar el contador del carrito
      } else {
        alert("Error al disminuir la cantidad.");
      }
    },
  });
}

// Función para eliminar un producto del carrito
function removeFromCart(id_producto) {
  $.ajax({
    url: "remove_from_cart.php", // Archivo PHP para eliminar un producto del carrito
    type: "POST",
    data: { id_producto: id_producto },
    success: function (response) {
      if (response == "success") {
        loadCartItems(); // Volver a cargar los ítems en el modal
        updateCartCount(); // Actualizar el contador del carrito
      } else {
        alert("Error al eliminar el producto.");
      }
    },
  });
}
// Función para aumentar la cantidad de un producto
function increaseQuantity(id_producto) {
  $.ajax({
    url: "increase_quantity.php",
    type: "POST",
    data: { id_producto: id_producto },
    success: function (response) {
      if (response == "success") {
        loadCartItems();
        updateCartCount();
      } else {
        alert("Error al aumentar la cantidad.");
      }
    },
  });
}
