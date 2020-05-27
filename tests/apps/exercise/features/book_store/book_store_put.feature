Feature: Put book
  In order store a book
  I want to store a book if not exists

  @removeFixtures
  Scenario: Check the api status
    Given I send a PUT request to "/books/057eb536-7e06-45d3-91ac-1bb8bd14a13c" with body:
    """
    {
      "title": "the title"
    }
    """

  @fixtures
  Scenario: Check the api status
    Given I send a PUT request to "/books/057eb536-7e06-45d3-91ac-1bb8bd14a13c" with body:
    """
    {
      "title": "the title"
    }
    """
    Then the response status code should be 400
