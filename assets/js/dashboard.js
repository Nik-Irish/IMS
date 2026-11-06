'use strict';

function readJsonData(id) {
    const element = document.getElementById(id);
    if (!element) throw new Error('Missing page data: ' + id);
    return JSON.parse(element.textContent);
}

function initializeDeleteConfirmations() {
    document.addEventListener('submit', event => {
        const form = event.target.closest('form[data-confirm]');
        if (form && !window.confirm(form.dataset.confirm)) {
            event.preventDefault();
        }
    });
}

function initializePasswordToggles() {
    document.querySelectorAll('.pw-toggle').forEach(button => {
        button.addEventListener('click', () => {
            const input = document.getElementById(button.dataset.target);
            if (!input) return;

            const showingPassword = input.type === 'password';
            input.type = showingPassword ? 'text' : 'password';
            button.textContent = showingPassword ? 'Hide' : 'Show';
        });
    });
}

function initializeProductSuggestions() {
    const nameInput = document.getElementById('f_name');
    const suggestionList = document.getElementById('productNameList');
    const productIdInput = document.getElementById('f_product_id');
    const categoryInput = document.getElementById('f_category');
    const priceInput = document.getElementById('f_price');
    if (!nameInput || !suggestionList || !productIdInput || !categoryInput || !priceInput) return;

    const products = readJsonData('existing-products-data');
    let autoFilledProduct = null;

    function closeSuggestionList() {
        suggestionList.style.display = 'none';
    }

    function fillProduct(product) {
        nameInput.value = product.name;
        productIdInput.value = product.code;
        categoryInput.value = product.category;
        priceInput.value = Number(product.price).toFixed(2);
        autoFilledProduct = {
            code: productIdInput.value,
            category: categoryInput.value,
            price: priceInput.value,
        };
    }

    function clearAutoFilledFields() {
        if (!autoFilledProduct) return;
        if (productIdInput.value === autoFilledProduct.code) productIdInput.value = '';
        if (categoryInput.value === autoFilledProduct.category) categoryInput.value = '';
        if (priceInput.value === autoFilledProduct.price) priceInput.value = '';
        autoFilledProduct = null;
    }

    function renderSuggestions() {
        const query = nameInput.value.trim().toLowerCase();
        const matches = products.filter(product =>
            !query || product.name.toLowerCase().includes(query));

        suggestionList.replaceChildren();
        if (!matches.length) {
            closeSuggestionList();
            return;
        }

        matches.forEach(product => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'pid-item';

            const name = document.createElement('span');
            name.className = 'pid-code';
            name.textContent = product.name;
            button.appendChild(name);

            const details = document.createElement('span');
            details.className = 'pid-meta';
            details.textContent = product.code + ' · ' + (product.category || 'General')
                + ' · Rs. ' + Number(product.price).toFixed(2);
            button.appendChild(details);

            button.addEventListener('mousedown', event => {
                event.preventDefault();
                fillProduct(product);
                closeSuggestionList();
            });
            suggestionList.appendChild(button);
        });
        suggestionList.style.display = 'block';
    }

    nameInput.addEventListener('focus', renderSuggestions);
    nameInput.addEventListener('input', () => {
        renderSuggestions();
        const query = nameInput.value.trim().toLowerCase();
        const product = products.find(item => item.name.toLowerCase() === query);
        if (product) {
            fillProduct(product);
            closeSuggestionList();
        } else {
            clearAutoFilledFields();
        }
    });

    document.addEventListener('click', event => {
        if (!event.target.closest('.pid-wrap')) closeSuggestionList();
    });
}

function initializeSaleForm() {
    const productSelect = document.getElementById('product_id');
    const unitPriceInput = document.getElementById('unit_price');
    const quantityInput = document.getElementById('quantity');
    const preview = document.getElementById('tax-preview');
    const customerNameInput = document.getElementById('customer_name');
    const customerPhoneInput = document.getElementById('customer_phone');
    const customerList = document.getElementById('customerList');
    if (!productSelect || !unitPriceInput || !quantityInput || !preview
        || !customerNameInput || !customerPhoneInput || !customerList) return;

    const customers = readJsonData('existing-customers-data');
    let selectedCustomer = null;

    function closeCustomerList() {
        customerList.style.display = 'none';
    }

    function renderCustomerList() {
        const query = customerPhoneInput.value.trim().toLowerCase();
        const matches = customers.filter(customer =>
            customer.phone && customer.phone.toLowerCase().includes(query));

        customerList.replaceChildren();
        if (!matches.length) {
            closeCustomerList();
            return;
        }

        matches.forEach(customer => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'pid-item';

            const phone = document.createElement('span');
            phone.className = 'pid-code';
            phone.textContent = customer.phone;
            button.appendChild(phone);

            const name = document.createElement('span');
            name.className = 'pid-meta';
            name.textContent = customer.name;
            button.appendChild(name);

            button.addEventListener('mousedown', event => {
                event.preventDefault();
                customerNameInput.value = customer.name;
                customerPhoneInput.value = customer.phone;
                selectedCustomer = customer;
                closeCustomerList();
            });
            customerList.appendChild(button);
        });
        customerList.style.display = 'block';
    }

    customerPhoneInput.addEventListener('focus', renderCustomerList);
    customerPhoneInput.addEventListener('input', () => {
        if (selectedCustomer && customerPhoneInput.value !== selectedCustomer.phone) {
            customerNameInput.value = '';
            selectedCustomer = null;
        }
        renderCustomerList();
    });
    customerNameInput.addEventListener('input', () => {
        if (selectedCustomer && customerNameInput.value !== selectedCustomer.name) {
            customerPhoneInput.value = '';
            selectedCustomer = null;
        }
    });

    document.addEventListener('click', event => {
        if (!event.target.closest('.customer-select-wrap')) closeCustomerList();
    });

    function formatCurrency(amount) {
        return 'Rs.' + amount.toFixed(2);
    }

    function updateSalePreview() {
        const unitPrice = Number.parseFloat(unitPriceInput.value) || 0;
        const quantity = Number.parseInt(quantityInput.value, 10) || 0;
        if (unitPrice <= 0 || quantity <= 0) {
            preview.style.display = 'none';
            return;
        }

        const subtotal = unitPrice * quantity;
        const tax = subtotal * 0.13;
        document.getElementById('prev-sub').textContent = formatCurrency(subtotal);
        document.getElementById('prev-tax').textContent = formatCurrency(tax);
        document.getElementById('prev-total').textContent = formatCurrency(subtotal + tax);
        preview.style.display = 'flex';
    }

    productSelect.addEventListener('change', () => {
        const option = productSelect.options[productSelect.selectedIndex];
        if (option.value) {
            unitPriceInput.value = option.dataset.price || '';
            quantityInput.max = option.dataset.stock || '';
        }
        updateSalePreview();
    });
    unitPriceInput.addEventListener('input', updateSalePreview);
    quantityInput.addEventListener('input', updateSalePreview);
}

initializeDeleteConfirmations();
initializePasswordToggles();
initializeProductSuggestions();
initializeSaleForm();
