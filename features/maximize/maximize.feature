Feature:
  In order to send a Booking request to our /maximize endpoint
  As a user
  I want to receive a structured array

  Scenario: It sends a request to '/maximize' path
    Given I have the following JSON array:
    """
    [
      {
        "request_id": "bookata_XY123",
        "check_in": "2020-01-01",
        "nights": 5,
        "selling_rate": 200,
        "margin": 20
      },
      {
        "request_id": "kayete_PP234",
        "check_in": "2020-01-04",
        "nights": 4,
        "selling_rate": 156,
        "margin": 5
      },
      {
        "request_id": "atropote_AA930",
        "check_in": "2020-01-04",
        "nights": 4,
        "selling_rate": 150,
        "margin": 6
      },
      {
        "request_id": "acme_AAAAA",
        "check_in": "2020-01-10",
        "nights": 4,
        "selling_rate": 160,
        "margin": 30
      }
    ]
    """
    When I send a POST request to "http://127.0.0.1:32777/maximize" with the JSON data
    Then the response status code should be 200
    And the response should contain JSON:
    """
    {
      "request_ids": [
        "bookata_XY123",
        "acme_AAAAA"
      ],
      "total_profit": 88,
      "avg_night": 10,
      "min_night": 8,
      "max_night": 12
    }
    """