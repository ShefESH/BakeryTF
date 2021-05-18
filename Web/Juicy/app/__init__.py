from flask import Flask
from flask_migrate import Migrate
from flask_dotenv import DotEnv

from flask_bootstrap import Bootstrap
from app.main import main

migrate = Migrate()


def create_app():
    app = Flask(__name__)
    Bootstrap(app)

    # Database setup
    # app.config.from_object(config_settings['development'])
    # app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False
    # app.config['SECRET_KEY'] =''

    # Add all models imported here
    # from app.models.assessor import Assessor

    # db.init_app(app)

    # with app.app_context():
    #     if db.engine.url.drivername == 'sqlite':
    #         migrate.init_app(app, db, render_as_batch=True)
    #     else:
    #         migrate.init_app(app, db)

    app.register_blueprint(main)

    return app
