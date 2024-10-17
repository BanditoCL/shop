<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PayPal JS SDK Standard Integration</title>
  <script 
    src="https://www.paypal.com/sdk/js?client-id=AX0P5pr5EOqpbFXKwqnK9dhDLMrsbYPDcX3Mu1nAKglo1KfOzZIJXcetwz6BtzAkfH-9fYAtpV2nQLra&buyer-country=US&currency=USD&components=buttons&enable-funding=venmo,paylater,card">
  </script>
</head>

<body>
  <div id="paypal-button-container"></div>

  <script>
    paypal.Buttons({
      style: {
        color: 'blue',
        shape: 'pill',
        label: 'pay'
      },
      createOrder: function(data, actions) {
        return actions.order.create({
          purchase_units: [{
            amount: {
              value: '10.00' // Asegúrate de usar un formato correcto con dos decimales
            }
          }]
        });
      },
      onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
          // window.location.href="confirmado.html"
          console.log(details);
        });
      },
      onCancel: function(data) {
        alert('Pago cancelado.');
      },
      onError: function(err) {
        console.error('Error en el pago:', err);
      }
    }).render('#paypal-button-container');
  </script>

</body>

</html>
