import { getUserRow, getUsersTableRows, navigate } from '../support/public/admin';
import { loginAs } from '../support/public/login';
import { resetFixtures } from '../support/fixtures';

const createJacoPastorius = () => {
    cy.findByLabelText('username à rechercher :').type('jacoPastorius');
    cy.findByText('Rechercher').click();
    cy.findByText('Valider').click();
    cy.findByLabelText(/Rôle à affecter au nouvel utilisateur/).select('ac_manager');

    cy.findByText(/Créer/).click();
};

describe('Admin page', () => {
    //beforeEach(resetFixtures);

    beforeEach(() => {
        navigate();
        // cy.findByText('Accès authentifié').click();
        loginAs({ login: 'lelongj', password: 'motdepasse' });
        cy.contains("Bienvenue sur l'interface de recherche des comptes auditeurs.").should('exist');
    });

    it('has no accessibility issues', () => {
        cy.injectAxe();
        //       cy.checkA11y();
    });

    /*
    it('shows a list of users', () => {
        getUsersTableRows().should('have.length', 5);
    });

    it('allows to create a user', () => {
        createJacoPastorius();

        getUsersTableRows().should('have.length', 6);
        getUserRow('53972b3c25c914.35850807DDD').within(() => cy.contains('gestionnaire AC'));
    });

    it('does not delete the user when cancelled', () => {
        createJacoPastorius();

        getUserRow('53972b3c25c914.35850807DDD').within(() => cy.findByText('Supprimer').click());
        getUserRow('53972b3c25c914.35850807DDD').within(() => cy.findByText('Annuler').click());

        getUsersTableRows().should('have.length', 6);
    });

    it('allows to delete a user with a confirmation', () => {
        createJacoPastorius();

        getUserRow('53972b3c25c914.35850807DDD').within(() => cy.findByText('Supprimer').click());
        getUserRow('53972b3c25c914.35850807DDD').within(() => cy.findByText('Confirmer').click());
        getUsersTableRows().should('have.length', 5);
    });
    */
});
