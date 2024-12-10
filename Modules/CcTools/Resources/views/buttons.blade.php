
<button type="button" id="donate-button" title="Donar a CodeCrafters" class="btn bg-yellow btn-flat m-6 btn-xs m-5 pull-right">
    <strong><i class="fab fa-paypal fa-lg"></i> Donar con PayPal</strong>
</button>

<button type="button" id="whatsapp-button" title="Enviar mensaje por WhatsApp" class="btn bg-green btn-flat m-6 btn-xs m-5 pull-right">
    <strong><i class="fab fa-whatsapp fa-lg"></i> Contactar por WhatsApp</strong>
</button>

<script src="https://www.paypalobjects.com/donate/sdk/donate-sdk.js" charset="UTF-8"></script>
<script>
    PayPal.Donation.Button({
        env: 'production',
        hosted_button_id: 'E7LUMXZTG7FE6',
        
    }).render('#donate-button');

    // Función para abrir WhatsApp con el número específico
    document.getElementById('whatsapp-button').addEventListener('click', function() {
        var phoneNumber = '+5218138965040'; 
        var message = 'Hola, me comunico directamente desde el ccTools.'; 
        var whatsappUrl = 'https://api.whatsapp.com/send?phone=' + encodeURIComponent(phoneNumber) + '&text=' + encodeURIComponent(message);
        window.open(whatsappUrl, '_blank');
    });
</script>


