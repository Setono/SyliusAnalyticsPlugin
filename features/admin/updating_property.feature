@managing_properties
Feature: Updating a property
  In order to change property details
  As an Administrator
  I want to be able to edit a property

  Background:
    Given the store has a property with tracking id "UA-12345678-1"
    And I am logged in as an administrator
    And the store operates on a single channel in "United States"

  @ui
  Scenario: Updating tracking id
    Given I want to update the property with tracking id "UA-12345678-1"
    When I update the property with tracking id "UA-98765432-1"
    And I save my changes
    Then I should be notified that it has been successfully edited
    And this property's tracking id should be "UA-98765432-1"
