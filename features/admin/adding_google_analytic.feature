@managing_google_analytic
Feature: Adding Google Analytic
  In order to add new google analytic to the store
  As an Administrator
  I want to be able to add new google analytic

  Background:
    Given I am logged in as an administrator
    And the store operates on a single channel in "United States"

  @ui
  Scenario: Adding product callout
    When I go to the create google analytic page
    And I fill the trackingId with "New Google Tracking"
    And I add it
    Then I should be notified that it has been successfully created
