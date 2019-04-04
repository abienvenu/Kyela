Feature: Add and delete comments
  In order to tell people about some specifics
  As a web user
  I should be able to add and remove comments

  Background:
    Given I am on the homepage
    When I follow "English"
    And I press "Create"
    And I fill in "Name" with "Test User"
    And I press "Add a participant"

  Scenario: Add a comment
    When I press "Add a comment"
    And I fill in "Author" with "Test User"
    And I fill in "Texte" with "This is a comment"
    And I press "Create"
    Then I should see "This is a comment" in the ".comments" element

  Scenario: Delete a comment
    When I press "Add a comment"
    And I fill in "Author" with "Test User"
    And I fill in "Texte" with "This is a comment"
    And I press "Create"
    And I press the 1st button containing a "edit" icon
    And I press "Delete"
    Then I should not see "This is a comment"
