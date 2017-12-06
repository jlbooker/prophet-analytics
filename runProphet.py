import time
import datetime
import pandas as pd
import numpy as np
from fbprophet import Prophet

print "Hello, world."

df = pd.read_csv('CancelledAppCounts-Combined.csv', header=0);

#print df

existingData = pd.DataFrame(columns=['ds', 'y'])

for row in df.iterrows():
    #print row
    #print row[1]['date']
    ds = datetime.datetime.fromtimestamp(row[1]['date'])
    existingData = existingData.append([{'ds': ds, 'y': row[1]['2012 Running Total']}])

existingData['floor'] = 0;
existingData['cap'] = 350;

m = Prophet(growth='logistic', weekly_seasonality=False)
m.fit(existingData)

#future = m.make_future_dataframe(periods=365)
future = pd.DataFrame({'ds': pd.date_range(start='3/1/2018', end='9/1/2018')})
future['floor'] = 0
future['cap'] = 350

forecast = m.predict(future)
print forecast

forecast.to_csv('prophet-forecast.csv')
