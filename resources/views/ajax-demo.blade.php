<h2>Ali Marketplace</h2>

<button id="addToCart">
    إضافة إلى السلة
</button>
<meta name="csrf-token" content="{{ csrf_token() }}">
<p>
    عدد القطع في السلة:
    <span id="cartCount">0</span>
</p>

<p id="message"></p>
<script>
    const add = document.getElementById('addToCart');
    const cart = document.getElementById('cartCount');
    const p = document.getElementById('message');

    add.addEventListener('click', async function () {

        console.log('تم الضغط على الاضافة');

        try {
            const response = await fetch("{{ route('ajax.quantity') }}", {
                method: 'POST',

                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content
                },

                body: JSON.stringify({
                    quantity: 0
                })
            });
            const data = await response.json();

            if (!response.ok) {
                p.textContent = data.message;
                return;
            }


            cart.textContent = data.quantity;
            p.textContent = data.message;

        } catch (error) {
            p.textContent = 'تعذر الاتصال بالسيرفر';
            console.error(error);
        }

    });
</script>