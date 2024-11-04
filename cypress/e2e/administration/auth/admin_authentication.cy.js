describe('Admin Authentication', () => {
  it('Successfully access admin backend.', () => {
    cy.loginAdmin('admin','admin')

  })
})
