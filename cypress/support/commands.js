// ***********************************************
// This example commands.js shows you how to
// create various custom commands and overwrite
// existing commands.
//
// For more comprehensive examples of custom
// commands please read more here:
// https://on.cypress.io/custom-commands
// ***********************************************
//
//
// -- This is a parent command --
// Cypress.Commands.add('login', (email, password) => { ... })
//
//
// -- This is a child command --
// Cypress.Commands.add('drag', { prevSubject: 'element'}, (subject, options) => { ... })
//
//
// -- This is a dual command --
// Cypress.Commands.add('dismiss', { prevSubject: 'optional'}, (subject, options) => { ... })
//
//
// -- This will overwrite an existing command --
// Cypress.Commands.overwrite('visit', (originalFn, url, options) => { ... })

// -- Administration Login --
Cypress.Commands.add('loginAdmin', (username, password) => {
    cy.session(
        username,
        () => {

            // Navigate to WP Admin login page.
            cy.visit('/wp-admin');

            // Specify credentials and submit.
            cy.get('#user_login').invoke('attr', 'value', username);
            cy.get('#user_pass').invoke('attr', 'value', password);
            cy.get('#wp-submit').click();
        },
        {
            validate: () => {
                //cy.getCookie('your-session-cookie').should('exist')
            },
        }
    )
})

// -- Frontend Login --
Cypress.Commands.add('loginApp', (username, password) => {
    cy.session(
        username,
        () => {

            // Navigate to Home Screen login page.
            cy.visit('/apps/login')

            // Extract nonce value, to be included within post request.
            cy.get('input[name="_wpnonce"]')
            .invoke('attr', 'value')
            .then(nonce => {

                // Create headers, also including nonce value.
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': nonce
                };

                // Submit post request to backend login controller.
                cy.request({
                    method: 'POST',
                    url: '/apps/login',
                    form: false,
                    headers: headers,
                    body: {
                        username: username,
                        password: password
                        //_wpnonce: nonce // DO NOT SPECIFY NONCE HERE, OR ENTIRE BODY WILL BE DELETED WITHIN BACKEND!
                    }
                })
                .then((response) => {

                    // Ensure we have an OK (200) response and force page refresh.
                    expect(response.status).to.eq(200);
                    cy.reload(true);
                });
            });
        },
        {
            validate: () => {
                //cy.getCookie('your-session-cookie').should('exist')
            },
        }
    )
})
