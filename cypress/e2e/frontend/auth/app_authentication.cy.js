describe('Frontend Authentication', () => {
  before(() => {
    cy.exec('composer install')
    cy.exec('composer update')
    cy.exec('npm install')
    cy.exec('npm run build')
    cy.exec('cp -R ./node_modules/@disciple.tools/web-components/dist/generated ./dist/assets/')
    cy.exec('cp -R ./node_modules/@disciple.tools/web-components/dist/lit-localize-*.js ./dist/assets/')

  })

  it('Successfully access home screen app.', () => {
    cy.loginApp('admin','admin')

  })
})
