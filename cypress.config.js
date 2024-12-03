import { defineConfig } from "cypress";

export default defineConfig({
  e2e: {
    setupNodeEvents(on, config) {
      // implement node event listeners here
    },
    baseUrl: 'http://dtdev.local'
  },
  dt: {
    credentials: {
      admin: {
        username: 'admin',
        password: 'admin'
      }
    }
  }
});
