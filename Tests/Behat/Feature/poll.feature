Feature: Create and manage polls
  In order to organize meetings
  As a web user
  I need to be able to create, edit and delete polls

  Background:
    Given I am on the homepage
    When I follow "English"
    And I fill in "Title" with "Test Poll"
    And I press "Create"

  Scenario: Create a poll
    Then I should see "Success"

  Scenario: Edit a poll
    When I press "Edit the poll"
    And I fill in "Title" with "Modified test poll"
    And I press "Save"
    Then I should see "Modified test poll"

  Scenario: Delete a poll
    When I press "Edit the poll"
    And I press "Delete"
    Then I should see "Success"

  Scenario: Setup access code
    When I press "Edit the poll"
    And I press "Setup an access code"
    And I fill in "Access code" with "1234"
    And I press "Save"
    And I press "Edit the poll"
    Then I should see "Someone setup an access code."
