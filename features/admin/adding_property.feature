@managing_properties
Feature: Adding a new property
  In order to use Google Analytics for my property
  As an Administrator
  I want to add a new property

  Background:
    Given I am logged in as an administrator
    And the store operates on a single channel in "United States"

  @ui
  Scenario: Adding a new property
    Given I want to create a new property
    When I fill the tracking id with "UA-12345678-1"
    And I add it
    Then I should be notified that it has been successfully created
    And the property "UA-12345678-1" should appear in the store
