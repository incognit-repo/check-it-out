Feature: Get book
  In order to obtain a book
  I want to see a book or exception

  @fixtures
  Scenario: Check the api status
    Given I send a GET request to "/books/057eb536-7e06-45d3-91ac-1bb8bd14a13c"
    Then the response content should be:
    """
    {
      "bookId":"057eb536-7e06-45d3-91ac-1bb8bd14a13c","title":"book title"
    }
    """

  Scenario: Check the api status
    Given I send a GET request to "/books/1"
    Then the response status code should be 404
