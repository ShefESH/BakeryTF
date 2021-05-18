from app import create_app


app = create_app()
app.secret_key = b'_5#y2L"F4Q8z\n\xec]/'

if __name__ == '__main__':
    app.run(debug=True)
