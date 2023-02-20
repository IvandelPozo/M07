import swaggerJSDoc from 'swagger-jsdoc';
import swaggerUi from 'swagger-ui-express';

const options = {
  definition: {
    openapi: '3.0.0',
    info: { title: 'Màquines Expenedores', version: '1.0.0' },
  },
  apis: ['./src/v1/routes/*.ts'],
};

const swaggerSpec = swaggerJSDoc(options);

const swaggerDocs = (app: any, port: any) => {
  app.use('/api/v1/docs', swaggerUi.serve, swaggerUi.setup(swaggerSpec));
  app.get('/api/v1/docs.json', (req: any, res: any) => {
    res.setHeader('Content-Type', 'application/json');
    res.send(swaggerSpec);
  });

  console.log(`Swagger Docs: http://localhost:${port}/api/v1/docs`);
};

export const V1SwaggerDocs = {
    swaggerDocs,
  };
