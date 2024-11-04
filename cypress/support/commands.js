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
      cy.visit('/wp-admin')
      /*cy.get('input[name=username]').type(username)
      cy.get('input[name=password]').type(`${password}{enter}`, { log: false })
      cy.url().should('include', '/dashboard')
      cy.get('h1').should('contain', username)*/

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
  /*cy.session(
    username,
    () => {*/

      // Navigate to Home Screen login page.
      cy.visit('/apps/login')

      // Update credential fields and submit.
      /*cy.get('dt-text[name="username"]').within(($form) => {
        cy.get('.input-group').within(($bob) => {
          cy.get('input').type(username)
        })
      })

      cy.get('dt-text[name="password"]').within(($form) => {
        cy.get('input').type(password)
      })*/

      cy.log('===============')
      cy.log(username)
      cy.log(password)

      /*cy.get('form').each(($form) => {
        if ($form.attr('action')) {
          cy.log($form.attr('action'))*/
          //$form.shadow()
          //var shadow = $form[0].attachShadow({mode: "open"})
          /*const childNodes = shadow.childNodes;
          for (const node of childNodes) {
            console.log(node.nodeName)
          }*/
        /*}
      })*/

      cy.get('input[name="_wpnonce"]')
      .invoke('attr', 'value')
      .then(nonce => {
        cy.log(nonce)

        /*cy.request('POST', '/apps/login', {
          username,
          password,
          _wpnonce: nonce
        })*/

        const headers = {
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        };

        cy.request({
          method: 'POST',
          url: '/apps/login',
          headers: headers,
          body: {
            username: username,
            password: password,
            _wpnonce: nonce
          }
        });
      });

      /*cy.get('form[action="http://dtdev.local/apps/login"]').shadow().find('input[name="_wpnonce"]')
      .invoke('attr', 'value')
      .then(nonce => {
        cy.log(nonce)
      })*/

      /*cy.get('form')[0].shadow().find('input[name="_wpnonce"]').invoke('attr', 'value')
      .then(nonce => {
        cy.log(nonce)
      })*

      /*cy.request('POST', '/apps/login', {
        username,
        password,
        _wpnonce: ''
      })*/

      /*cy.get('dt-text[name="username"]').invoke('attr', 'value', username)
      cy.get('dt-text[name="username"]').shadow().find('input[name="username"]').type(username)

      cy.get('dt-text[name="password"]').invoke('attr', 'value', password)
      cy.get('dt-text[name="password"]').shadow().find('input[name="password"]').type(password, {log: false})
      //...cy.get('dt-text[name="password"]').shadow().find('input[name="password"]').type(`${password}{enter}`, {log: false})

      cy.get('form').each(($form) => {
        if ($form.attr('action')) {
          cy.log($form.attr('action'))
          cy.get(`form[action="${$form.attr('action')}"]`).submit()
        }
      })*/



      //cy.get('dt-text[name="username"]').invoke('attr', 'value', username)
      //cy.get('dt-text[name="password"]').invoke('attr', 'value', password)
      //cy.get('dt-text[name="password"]').type(`${password}{enter}`, {log: false})

      //cy.get('sp-button[type="submit"]').click()

      //cy.get('form').submit()

      // Assert a valid login has occurred and url updated.
      //cy.url().should('include', '/launcher')

    /*},
    {
      validate: () => {
        //cy.getCookie('your-session-cookie').should('exist')
      },
    }
  )*/
})
