describe("Homepage compactada", () => {
    beforeEach(() => {
        cy.viewport(1000, 768);
        cy.visit("https://experimente.mapas.tec.br/");
    });

    it("acessa \"Projetos\" no navbar", () => {
        cy.get(".mc-header-menu__btn-mobile").click();
        cy.contains(".mc-header-menu__itens a", "Projetos").click();
        cy.url().should("include", "/projetos/");
    });
});

describe("Pagina de Projetos", () => {
    beforeEach(() => {
        cy.viewport(1000, 768);
        cy.visit("https://experimente.mapas.tec.br/projetos");
    });

    it("Clica em \"Acessar\" e entra na pagina no projeto selecionado", () => {
        cy.get(':nth-child(2) > .entity-card__footer > .entity-card__footer--action > .button').click();
        cy.url().should("include", "/projeto/");
    });
});
