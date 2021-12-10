from pyqode.core import backend

if __name__ == '__main__':
    # configure the code completion providers, here we just use a basic one
    backend.CodeCompletionWorker.providers.append(
        backend.DocumentWordsProvider())
    backend.serve_forever()