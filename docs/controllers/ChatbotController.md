# ChatbotController

**Namespace**: `App\Controllers`  
**Extends**: `BaseController`

The `ChatbotController` is a simple interface designed to handle Natural Language Processing (NLP) queries or automated chat responses.

## Endpoints

### 1. Ask Chatbot
- **Route**: `POST /chatbot/ask`
- **Description**: Receives a user's text query and processes a string response.
- **Notes**: Currently implemented as a basic endpoint. Can be extended to connect to an external AI Service (like OpenAI or Dialogflow) to answer common client questions regarding business hours, service prices, or booking assistance.
