document.addEventListener('DOMContentLoaded', function () {
    const setIncomeBtn = document.querySelector('#set-income-btn');
    const editIncomeBtn = document.querySelector('#edit-income-btn');
    const inputSection = document.querySelector('#input-section');
    const balanceSection = document.querySelector('#balance-section');
    const balanceDisplay = document.querySelector('#balance');
    const salaryDisplay = document.querySelector('#salary');
    const inputBalance = document.querySelector('#input-balance');
    const inputSalary = document.querySelector('#input-salary');
    const overlay = document.getElementById('overlay'); // Get overlay

    // Fetch balance and salary from backend
    function fetchIncomeData() {
        fetch('fetch_income.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const balance = data.balance;
                const salary = data.salary;
                if (balance > 0 || salary > 0) {
                    // Display balance and salary
                    balanceDisplay.textContent = `₱${balance.toFixed(2)}`;
                    salaryDisplay.textContent = `₱${salary.toFixed(2)}`;
                    inputSection.style.display = 'none';
                    balanceSection.style.display = 'block';
                } else {
                    // If balance and salary are empty or zero, show the input fields
                    inputSection.style.display = 'block';
                    balanceSection.style.display = 'none';
                }
            } else {
                // If there was an issue with fetching income data, show input section
                inputSection.style.display = 'block';
                balanceSection.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error fetching income data:', error);
            inputSection.style.display = 'block';
            balanceSection.style.display = 'none';
        });
    }

    // Call fetchIncomeData to load balance and salary
    fetchIncomeData();

    setIncomeBtn.addEventListener('click', function () {
        let balance = parseFloat(inputBalance.value) || 0;
        let salary = parseFloat(inputSalary.value) || 0;
    
        // Validation
        if (isNaN(balance) || balance <= 0 || isNaN(salary) || salary <= 0) {
            document.getElementById('balance-error').style.display = 'block';
            document.getElementById('salary-error').style.display = 'block';
            return;
        }
    
        // Hide error messages
        document.getElementById('balance-error').style.display = 'none';
        document.getElementById('salary-error').style.display = 'none';
    
        // Update the displayed values
        balanceDisplay.textContent = `₱${balance.toFixed(2)}`;
        salaryDisplay.textContent = `₱${salary.toFixed(2)}`;
    
        // Store values in the database
        fetch('update_income.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `balance=${balance.toFixed(2)}&salary=${salary.toFixed(2)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Successfully updated the database
                localStorage.setItem('balance', balance.toFixed(2));
                localStorage.setItem('salary', salary.toFixed(2));
                inputSection.style.display = 'none';
                balanceSection.style.display = 'block';
            } else {
                // Handle error updating the database
                alert('Error updating income data.');
            }
        })
        .catch(error => console.error('Error updating income data:', error));
    });
    

    editIncomeBtn.addEventListener('click', function () {
        // Display the current balance and salary for editing
        inputBalance.value = balanceDisplay.textContent.slice(1); // Remove ₱ sign
        inputSalary.value = salaryDisplay.textContent.slice(1); // Remove ₱ sign
        inputSection.style.display = 'block';
        balanceSection.style.display = 'none';
    });

    const themeSwitch = document.getElementById('theme-switch');
    const addExpenseBtn = document.getElementById('add-expense-btn');
    const descriptionInput = document.getElementById('description');
    const amountInput = document.getElementById('expense-amount');
    const expenseList = document.getElementById('expense-list');
    const totalExpenseSection = document.getElementById('total-expense-section');
    const totalExpenseDisplay = document.getElementById('total-expense');

    // Retrieve expenses from localStorage (if any)
    let expenses = JSON.parse(localStorage.getItem('expenses')) || [];

    // Display stored expenses
    expenses.forEach(function (expense) {
        addExpenseToTable(expense.description, expense.amount, expense.date);
    });

    themeSwitch.addEventListener('change', () => {
        document.body.classList.toggle('dark-mode'); // Make sure it matches the CSS class
    });

    addExpenseBtn.addEventListener('click', function () {
        const description = descriptionInput.value;
        const amount = parseFloat(amountInput.value);

        // Hide error messages
        document.getElementById('description-error').style.display = 'none';
        document.getElementById('amount-error').style.display = 'none';

        let validInput = true;

        if (!description) {
            document.getElementById('description-error').style.display = 'block';
            validInput = false;
        }
        if (isNaN(amount) || amount <= 0) {
            document.getElementById('amount-error').style.display = 'block';
            validInput = false;
        }

        if (validInput) {
            const expense = {
                description: description,
                amount: amount,
                date: new Date().toLocaleString('en-US', { hour12: true })
            };

            // Add expense to localStorage
            expenses.push(expense);
            localStorage.setItem('expenses', JSON.stringify(expenses));

            // Add expense to table
            addExpenseToTable(expense.description, expense.amount, expense.date);

            // Clear inputs
            descriptionInput.value = '';
            amountInput.value = '';

            // Update total expense
            updateTotalExpense();
        }
    });

    function addExpenseToTable(description, amount, date) {
        const row = document.createElement('tr');

        const descriptionCell = document.createElement('td');
        descriptionCell.textContent = description;

        const amountCell = document.createElement('td');
        amountCell.textContent = `₱${amount.toFixed(2)}`;

        const dateCell = document.createElement('td');
        dateCell.textContent = date;

        const actionsCell = document.createElement('td');

        const editBtn = document.createElement('button');
        editBtn.classList.add('edit-button');
        const editIcon = document.createElement('img');
        editIcon.src = 'edit.png';
        editIcon.classList.add('icon');
        editBtn.appendChild(editIcon);
        editBtn.appendChild(document.createTextNode(' Edit'));

        const deleteBtn = document.createElement('button');
        deleteBtn.classList.add('delete-button');
        const deleteIcon = document.createElement('img');
        deleteIcon.src = 'delete.png';
        deleteIcon.classList.add('icon');
        deleteBtn.appendChild(deleteIcon);
        deleteBtn.appendChild(document.createTextNode(' Delete'));

        const paidBtn = document.createElement('button');
        paidBtn.classList.add('paid-button');
        const paidIcon = document.createElement('img');
        paidIcon.src = 'paid.png';
        paidIcon.classList.add('icon');
        paidBtn.appendChild(paidIcon);
        paidBtn.appendChild(document.createTextNode(' Paid'));

        actionsCell.appendChild(editBtn);
        actionsCell.appendChild(deleteBtn);
        actionsCell.appendChild(paidBtn);

        row.appendChild(descriptionCell);
        row.appendChild(amountCell);
        row.appendChild(dateCell);
        row.appendChild(actionsCell);

        expenseList.appendChild(row);

        // Update the total expense when a new expense is added
        updateTotalExpense();

        // Edit expense functionality
        editBtn.addEventListener('click', function () {
            const saveBtn = document.createElement('button');
            saveBtn.classList.add('save-button');

            const saveIcon = document.createElement('img');
            saveIcon.src = 'save.png';
            saveIcon.classList.add('icon');
            saveBtn.appendChild(saveIcon);
            saveBtn.appendChild(document.createTextNode(' Save'));

            const cancelBtn = document.createElement('button');
            cancelBtn.classList.add('cancel-button');

            const cancelIcon = document.createElement('img');
            cancelIcon.src = 'cancel.png';
            cancelIcon.classList.add('icon');
            cancelBtn.appendChild(cancelIcon);
            cancelBtn.appendChild(document.createTextNode(' Cancel'));

            actionsCell.replaceChild(saveBtn, editBtn);
            actionsCell.appendChild(cancelBtn);

            const descriptionInput = document.createElement('input');
            descriptionInput.type = 'text';
            descriptionInput.value = descriptionCell.textContent;
            descriptionCell.textContent = '';
            descriptionCell.appendChild(descriptionInput);

            const amountInput = document.createElement('input');
            amountInput.type = 'number';
            amountInput.value = amountCell.textContent.slice(1);
            amountCell.textContent = '';
            amountCell.appendChild(amountInput);

            saveBtn.addEventListener('click', function () {
                descriptionCell.textContent = descriptionInput.value;
                amountCell.textContent = `₱${parseFloat(amountInput.value).toFixed(2)}`;
                actionsCell.replaceChild(editBtn, saveBtn);
                actionsCell.removeChild(cancelBtn);

                // Update the total after editing
                updateTotalExpense();
            });

            cancelBtn.addEventListener('click', function () {
                descriptionCell.textContent = description;
                amountCell.textContent = `₱${amount.toFixed(2)}`;
                actionsCell.replaceChild(editBtn, saveBtn);
                actionsCell.removeChild(cancelBtn);
            });
        });

        // Delete expense functionality
        deleteBtn.addEventListener('click', function () {
            showNotification(`Are you sure you want to delete "${description}"?`, function () {
                expenses = expenses.filter(exp => exp.description !== description || exp.amount !== amount);
                localStorage.setItem('expenses', JSON.stringify(expenses));
                expenseList.removeChild(row);
                updateTotalExpense();
            });
        });

        // Paid expense functionality
        paidBtn.addEventListener('click', function () {
            showNotification(`Have you successfully paid "${description}"?`, function () {
                expenses = expenses.filter(exp => exp.description !== description || exp.amount !== amount);
                localStorage.setItem('expenses', JSON.stringify(expenses));
                expenseList.removeChild(row);
                updateTotalExpense();
            });
        });
    }

    function showNotification(message, onConfirm) {
        const notification = document.getElementById('notification');
        const notificationMessage = document.getElementById('notification-message');
        const yesBtn = document.getElementById('yes-btn');
        const noBtn = document.getElementById('no-btn');

        notificationMessage.textContent = message;
        notification.style.display = 'flex';
        overlay.style.display = 'block'; // Show the overlay

        yesBtn.onclick = function () {
            onConfirm();
            closeNotification();
        };

        noBtn.onclick = function () {
            closeNotification();
        };
    }

    function closeNotification() {
        const notification = document.getElementById('notification');
        const overlay = document.getElementById('overlay');
        notification.style.display = 'none';
        overlay.style.display = 'none';
    }

    // Function to update the total expense
    function updateTotalExpense() {
        const rows = expenseList.getElementsByTagName('tr');
        let total = 0;

        for (let row of rows) {
            const amountCell = row.getElementsByTagName('td')[1];
            const amount = parseFloat(amountCell.textContent.slice(1));
            total += amount;
        }

        // If there are no expenses, hide the total section
        if (total > 0) {
            totalExpenseSection.style.display = 'block';
            totalExpenseDisplay.textContent = `₱${total.toFixed(2)}`;
        } else {
            totalExpenseSection.style.display = 'none';
        }
    }

    // Clock functionality
    function updateClock() {
        const now = new Date();
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            second: '2-digit',
            hour12: true // Set this to true for AM/PM format
        };
        const dateTimeString = now.toLocaleString('en-US', options);
        document.getElementById('clock').textContent = dateTimeString;
    }

    // Update the clock immediately and set an interval to update every second
    updateClock();
    setInterval(updateClock, 1000);
});