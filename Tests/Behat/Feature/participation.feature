Feature: Setup and update participations
  In order to tell my availability for a given event
  As a web user
  I should be able to setup and update my participation status

  Background:
    Given I am on the homepage
    When I follow "English"
    And I fill in "Title" with "Test Poll"
    And I press "Create"
    And I fill in "Name" with "Test User"
    And I press "Add a participant"
    And I press "Suggest a date"
    And I fill in "Date" with "01-01-2100"
    And I press "Create"

  @javascript
  Scenario: Setup particicipation
    When I press "-"
    And I press "Yes"
    And I wait for the participation to update
    Then I should see "Yes"

  @javascript
  Scenario: Modify participation
    When I press "-"
    And I press "Yes"
    And I wait for the participation to update
    And I press "Yes"
    And I press "Maybe"
    And I wait for the participation to update
    Then I should see "Maybe"

  @javascript
  Scenario: Delete participation
    When I press "-"
    And I press "Yes"
    And I wait for the participation to update
    And I press "Yes"
    And I press "trash"
    And I wait for the participation to update
    Then I should not see "Yes"
