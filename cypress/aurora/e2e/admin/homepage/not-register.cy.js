describe("Fluxo de cadastro — não-logado vs logado", () => {
    const user = {
        email: "alessandrofeitoza@example.com",
        password: "Aurora@2024",
    };

    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.clearCookies();
        cy.clearLocalStorage();
    });

    it("mostra link de Cadastro quando não-logado e bloqueia ao logar", () => {
        cy.visit("/");

        cy.contains("a", "Cadastro")
            .should("be.visible")
            .and("have.attr", "href", "/cadastro");

        cy.login(user.email, user.password);

        cy.visit("/");

        cy.contains(
            "Você não pode se cadastrar enquanto estiver logado"
        ).should("be.visible");

        cy.contains("a", "Cadastro").should("not.exist");
    });
});
